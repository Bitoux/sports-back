<?

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Spot;
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
}