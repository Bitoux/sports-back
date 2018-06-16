<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Map;
use AppBundle\Entity\User;
use AppBundle\Entity\Filter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;



class MapController extends BaseController
{

    /**
     * @Rest\Post("/map/{id}/spots")
     * @Rest\View(StatusCode = 200)
     * @ParamConverter(
     *     "map",
     *     converter="fos_rest.request_body"
     * )
     */
    public function addUserSpots(Map $map, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }

        $mapRes = $this->getMapRepository()->find($map->getId());

        $spots = array();

        foreach($map->getSpots() as $spot){
            if($spot->getId() !== null){
                $tmpSpot = $this->getSpotRepository()->find($spot->getId());
                array_push($spots, $tmpSpot);
            } else {
                array_push($spots, $spot);
            }

        }

        $mapRes->setSpots($spots);

        $this->getDoctrine()->getManager()->persist($mapRes);
        $this->getDoctrine()->getManager()->flush();

        return $mapRes;
    }

}
