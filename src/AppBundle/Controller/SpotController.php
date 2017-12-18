<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Spot;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SpotController extends BaseController
{

	/**
	 * @Rest\Get("/spots", name="spots_list")
	 * @Rest\View
	 *
	 * @ApiDoc(
	 *  	section = "Spot CRUD",
	 *		description = "Get the list of all spots",
	 *		statusCodes = {
	 *   			200 = "OK",
	 *   }
	 * )
	 *
	 */
	public function getSpots()
	{
		$spots = $this->getSpotRepository()->findAll();

		return $spots;
	}

	/**
	 * @Rest\Get("/spots/{id}/get", name="spot_detail")
	 * @Rest\View
	 * @ApiDoc(
	 *  	section = "Spot CRUD"
	 *		description = "Get the detail of one spot",
	 *		requirements = {
	 *			{ "name"="id", "dataType="integer", "requirement"="\d+", "description"="ID spot" }
	 *		},
	 *    statusCodes = {
	 *   			200 = "OK",
	 *   	}
	 * )
	 */
	public function getSpotById($spotID){
		$spot = $this->getSpotRepository()->find($spotID);

		if($spot){
			return $spot;
		}

	}

	/**
	 * @Rest\Delete("/spots/{id}/delete")
	 * @Rest\View
	 * @ApiDoc(
	 *  	section = "Spots CRUD",
	 *	  description = "delete a spot",
	 *    requirements={
	 *   			{ "name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID du spot" }
	 *    },
	 *   statusCodes={
	 *   		200 = "OK",
	 *   }
	 * )
	 */
	public function deleteSpot($idSpot){
		$spot = $this->getSpotRepository()->find($idSpot);
		$this->getDoctrine()->getManager()->remove($spot);
		$this->getDoctrine()->getManager()->flush();

		return new JsonResponse(array("status" => 200));
	}

	/**
	 * @Rest\Post("/spots/edit")
	 * @Rest\View(StatusCode = 200)
	 * @ParamConverter(
	 *  	"spot" ,
	 *   converter="fos_rest.request_body",
	 * )
	 */
	public function editSpot(Spot $spot){
		$spotUpdate = $this->getSpotRepository()->find($spot->getId());

		$spotUpdate->setLongitude($spot->getLongitude());
		$spotUpdate->setLatitude($spot->getLatitude());
		$spotUpdate->setName($spot->getName());
		$spotUpdate->setFilters($spot->getFilters());
		$spotUpdate->setGrades($spot->getGrades());

	}
}
