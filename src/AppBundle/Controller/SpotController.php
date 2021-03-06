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

class SpotController extends BaseController
{

	/**
	 * @Rest\Get("/spots", name="spots_list")
	 * @Rest\View(StatusCode = 200)
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

		$events = array();

		foreach($spot->getEvents() as $event){

			if($event->getId()){
				$tmpEvent = $this->getEventRepository()->find($event->getId());
				array_push($events, $tmpEvent);
			}else{
        $event->setSpot($spotRes);
				array_push($events, $event);
			}
		}

		$spotRes->setEvents($events);

		$this->getDoctrine()->getManager()->persist($event);
		$this->getDoctrine()->getManager()->persist($spotRes);
		$this->getDoctrine()->getManager()->flush();


		return $spotRes;
	}


	/**
	 * @Rest\Put("/spot/{id}/edit")
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
		$filters = array();

		foreach ($spot->getFilters() as $filter){
			$tmpFilter = $this->getFilterRepository()->find($filter->getId());
			array_push($filters, $tmpFilter);
		}

		$spotRes->setSpot(
			$spot->getAddress(),
			$spot->getLatitude(),
			$spot->getLongitude(),
			$spot->getName(),
			$spot->getEvents(),
			$filters
		);

		$this->getDoctrine()->getManager()->persist($spotRes);
		$this->getDoctrine()->getManager()->flush();

		return $spotRes;
	}

	/**
	 * @Rest\Delete("/spots/{id}/delete")
	 * @Rest\View
	 * @param $id
	 * @return JsonResponse
	 */
	public function deleteSpot($id){
		$spot = $this->getSpotRepository()->find($id);
		$this->getDoctrine()->getManager()->remove($spot);
		$this->getDoctrine()->getManager()->flush();

		return new JsonResponse(array("status" => 200));
	}

}
