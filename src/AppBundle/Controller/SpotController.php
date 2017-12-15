<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
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
}
