<?

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
	 * @Rest\Post("/spot/{id}/event")
	 * @Rest\View(StatusCode = 200)
	 * @ParamConverter(
	 *   "spot",
	 *   converter="fos_rest.request_body"
	 * )
	 */
	public function addSpotEvent(Spot $spot, ConstraintViolationList $violations){
		if(count($violations)){
			$message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
			foreach($violations as $violation){
				$message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
			}
			throw new ResourceValidationException($message);
		}

		$spotRes = $this->getSpotRepository()->find($spot->getId());

		$spotRes->setEvents($spot->getEvents());

		$this->getDoctrine()->getManager()->persist($spotRes);
		$this->getDoctrine()->getManager()->flush();


		return $spotRes;
	}

	/**
	 * @Rest\Post("/spot/{id}/filters")
	 * @Rest\View(StatusCode = 200)
	 * @ParamConverter(
	 *   "spot",
	 *   converter="fos_rest.request_body"
	 * )
	 */
	public function addSpotFilters(Spot $spot, ConstraintViolationList $violations, $filter){
		if(count($violations)){
			$message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
			foreach($violations as $violation){
				$message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
			}
			throw new ResourceValidationException($message);
		}

		$spotRes = $this->getSpotRepository()->find($spot->getId());

		$filters = $spot->getFilters();

		foreach($filter as $filters){
			$filterRes = $this->getFilterRepository()->find($filter->getId());
			if(!$spotRes->getFilters()->contains($filterRes)){
				$spotRes->addFilter($filterRes);
			}
		}

		$this->getDoctrine()->getManager()->persist($spotRes);
		$this->getDoctrine()->getManager()->flush();

		return $spotRes;
	}

	/**
	 * @Rest\Post("/spot/{id}/edit")
	 * @Rest\View(StatusCode = 200)
	 * @ParamConverter(
	 *   "spot",
	 *   converter="fos_rest.request_body"
	 * )
	 */
	public function updateSpot(Spot $spot, ConstraintViolationList $violations){
		if (count($violations)) {
			$message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
			foreach ($violations as $violation) {
				$message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
			}
			throw new ResourceValidationException($message);
		}

		$spotRes = $this->getSpotRepository()->find($spot->getId());

		$spotRes->setSpot(
			$spot->getAddress(),
			$spot->getLatitude(),
			$spot->getLongitude(),
			$spot->getName(),
			$spot->getEvents(),
			$spot->getGrades(),
			$spot->getFilters()
		);

		$this->getDoctrine()->getManager()->persist($spotRes);
		$this->getDoctrine()->getManager()->flush();

		return $spotRes;
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
