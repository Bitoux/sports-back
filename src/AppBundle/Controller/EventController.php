<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;

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