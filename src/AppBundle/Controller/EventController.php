<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Event;
use AppBundle\Entity\Spot;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;
use Symfony\Component\HttpFoundation\Request;

class EventController extends BaseController
{

	/**
	 * @Rest\Get("/events", name="event_list")
	 * @Rest\View
	 */
	public function getEvents(){
		$events = $this->getEventRepository()->findAll();

		return $events;
	}

	/**
	 * @Rest\Get("/events/{id}/get", name="event_single")
	 * @Rest\View
	 */
	public function getEventsById($id){
		$event = $this->getEventRepository()->find($id);

		return $event;
	}

	/**
	 * @Rest\Delete("/events/{id}/delete")
	 * @Rest\View
	 */
	public function deleteEvent($id){
		$event = $this->getEventRepository()->find($id);
		$this->getDoctrine()->getManager()->remove($event);
		$this->getDoctrine()->getManager()->flush();

		return new JsonResponse (array("status" => 200));
	}

	/**
	 * @Rest\Put("/events/{id}/edit")
	 * @Rest\View(StatusCode = 200)
	 * @ParamConverter(
	 *   "event",
	 *   converter="fos_rest.request_body"
	 * )
	 */
	public function updateEvent(Event $event, ConstraintViolationList $violations){
		if (count($violations)) {
			$message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
			foreach ($violations as $violation) {
				$message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
			}
			throw new ResourceValidationException($message);
		}

		$eventRes = $this->getEventRepository()->find($event->getId());

		$eventRes->setEvent(
			$event->getDate(),
			$event->getHour(),
			$event->getTime(),
			$event->getLevel(),
			$event->getPrice(),
			$event->getDescription(),
			$event->getNbUser()
		);

        $users = array();

        foreach($event->getUsers() as $user){
            $tmpUser = $this->getuserRepository()->find($user->getId());
            array_push($users, $tmpUser);
        }

        $eventRes->setUsers($users);

        $filters = array();

        foreach ($event->getFilters() as $filter){
            $tmpFilter = $this->getFilterRepository()->find($filter->getId());
            array_push($filters, $tmpFilter);
        }

        $eventRes->setFilters($filters);


		$this->getDoctrine()->getManager()->persist($eventRes);
		$this->getDoctrine()->getManager()->flush();

		return $eventRes;
	}

	/**
     * @Rest\Get("/proevents/get/all", name="all_proevent")
     * @Rest\View(StatusCode = 200)
     */
    public function getAllProEvent()
    {
        $proEvents = $this->getDoctrine()->getRepository(Event::class)->getProEvent();

        return $proEvents;
    }

	/**
     * @Rest\Post("/proevents/edit", name="edit_proevent")
     * @Rest\View(StatusCode = 200)
     */
	public function editSpotEvent(Request $request){
		$inputFilters = $request->get('filters');
		$filters = array();

		// GET FILTERS
		foreach($inputFilters as $index=>$filter){
			if($filter){
				$filter = $this->getFilterRepository()->find($index+1);
				array_push($filters, $filter);
			}
		}

		$event = $this->getEventRepository()->find($request->get('id'));
		$event->setDate($request->get('date'));
		$event->setHour($request->get('hour'));
		$event->setDescription($request->get('description'));
		$event->setFilters($filters);

		$spot = $this->getSpotRepository()->find($request->get('spotID'));
		$spot->setLongitude($request->get('longitude'));
		$spot->setLatitude($request->get('latitude'));
		$spot->setName($request->get('name'));
		$spot->setAddress($request->get('address'));
		$spot->setDescription($request->get('description'));

		$this->getDoctrine()->getManager()->persist($spot);
		$this->getDoctrine()->getManager()->persist($event);
        $this->getDoctrine()->getManager()->flush();

		return $event;
	}

	/**
     * @Rest\Post("/proevents/create", name="create_proevent")
     * @Rest\View(StatusCode = 200)
     */
	public function createSpotEvent(Request $request){
		$inputFilters = $request->get('filters');
		$filters = array();

		// GET FILTERS
		foreach($inputFilters as $index=>$filter){
			$filter = $this->getFilterRepository()->find($index+1);
			array_push($filters, $filter);
		}

		// SET EVENT
		$user = $this->getUserRepository()->find($request->get('user_id'));
		$event = new Event();
		$event->setOwner($user);
		$event->setDate($request->get('date'));
		$event->setHour($request->get('hour'));
		$event->setDescription($request->get('description'));
		$event->setFilters($filters);
		
		// SET SPOT
		$spot = new Spot();
		$spot->setLongitude($request->get('longitude'));
		$spot->setLatitude($request->get('latitude'));
		$spot->setName($request->get('name'));
		$spot->setAddress($request->get('address'));
		$spot->setDescription($request->get('description'));

		$event->setSpot($spot);

		// SET MAP
		$map = $this->getMapRepository()->find($request->get('idMap'));

		$map->addSpot($spot);

		$this->getDoctrine()->getManager()->persist($spot);
		$this->getDoctrine()->getManager()->persist($event);
        $this->getDoctrine()->getManager()->flush();

		return $event;
	}

	/**
     * @Rest\Get("/proevents/{id}/user", name="event_user")
     * @Rest\View(StatusCode = 200)
     */
	public function getProEventsByUser($id){
		$events = $this->getEventRepository()->findBy(
			['owner' => $id]
		);

		return $events;
	}
	

	/**
	 * @Rest\Post("/events/create")
	 * @Rest\View(StatusCode = 200)
	 * @ParamConverter(
	 *   "event",
	 *   converter="fos_rest.request_body"
	 * )
	 *
	 */
	public function addEvent(Event $event, ConstraintViolationList $violations){
		if (count($violations)) {
			$message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
			foreach ($violations as $violation) {
				$message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
			}
			throw new ResourceValidationException($message);
		}

		$newEvent = new Event();
		$newEvent->setDate($event->getDate());
		$newEvent->setHour($event->getHour());
		$newEvent->setTime($event->getTime());
		$newEvent->setLevel($event->getLevel());
		$newEvent->setPrice($event->getPrice());
		$newEvent->setNbUser($event->getNbUser());
		$newEvent->setDescription($event->getDescription());

        $ownerRes = $this->getUserRepository()->find($event->getOwner()->getId());
        $newEvent->setOwner($ownerRes);

		$spotRes = $this->getSpotRepository()->find($event->getSpot()->getId());
		$spotRes->addEvent($newEvent);

        $users = array();

        foreach($event->getUsers() as $user){
            $tmpUser = $this->getuserRepository()->find($user->getId());
            array_push($users, $tmpUser);
        }

        $newEvent->setUsers($users);

        $filters = array();

        foreach ($event->getFilters() as $filter){
            $tmpFilter = $this->getFilterRepository()->find($filter->getId());
            array_push($filters, $tmpFilter);
        }

        $newEvent->setFilters($filters);

		$this->getDoctrine()->getManager()->persist($newEvent);
		$this->getDoctrine()->getManager()->flush();

		return $newEvent;
	}
}