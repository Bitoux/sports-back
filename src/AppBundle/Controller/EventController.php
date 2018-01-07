<?

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class EventController extends BaseController
{

	/**
	 * @Rest\Get("/events", name="event_list")
	 * @Rest\View
	 * @ApiDoc(
	 *     section = "Filter CRUD",
	 *     description = "Get the list of all filters",
	 *     statusCodes = {
	 *         200 = "OK",
	 *     }
	 * )
	 */
	public function getEvents(){
		$events = $this->getEventRepository()->findAll();

		return $events;
	}

	/**
	 * @Rest\Delete("/events/{id}/delete")
	 * @Rest\View
	 * @ApiDoc(
	 *   section="Event Crud",
	 *   description="Delete a event",
	 *   requirements = {
	 *         { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID du dossier" }
	 *     },
	 *   statusCodes={
	 *   		200="OK"
	 *	 }
	 * )
	 */
	public function deleteEvent($id){
		$event = $this->getEventRepository()->find($id);
		$this->getDoctrine()->getManager()->remove($event);
		$this->getDoctrine()->getManager()->flush();

		return new JsonResponse (array("status" => 200));
	}

	/**
	 * @Rest\Post("/events/{id}/edit")
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
			$event->getName(),
			$event->getDate(),
			$event->getSubject(),
			$event->getDescription(),
			$event->getNbUser()
		);

		$this->getDoctrine()->getManager()->persist($eventRes);
		$this->getDoctrine()->getManager()->flush();

		return $eventRes;
	}
}