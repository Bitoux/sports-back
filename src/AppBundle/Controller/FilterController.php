<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Filter;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;



class FilterController extends BaseController
{
    /**
     * @Rest\Get("/filters", name="filter_list")
     * @Rest\View
     *
     * @ApiDoc(
     *     section = "Filter CRUD",
     *     description = "Get the list of all filters",
     *     statusCodes = {
     *         200 = "OK",
     *     }
     * )
     */
    public function getFilters()
    {
        $filters = $this->getFilterRepository()->findAll();

        return $filters;
    }

    /**
     * @Rest\Post("/filters/create")
     * @Rest\View(StatusCode = 201)
     * @ApiDoc(
     *     section = "Filter CRUD",
     *     description = "Create a user in the database",
     *     requirements = {
     *         { "name"="designation", "dataType"="string", "description"="Nom du filtre" }
     *     },
     *     statusCodes = {
     *         201 = "Created",
     *     }
     * )
     */
    public function createUser(Filter $filter, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($filter);
        $em->flush();

        return $filter;
    }


    /**
     * @Rest\Delete("/filters/{id}/delete")
     * @Rest\View
     * @ApiDoc(
     *     section = "Filter CRUD",
     *     description = "delete a filter",
     *     requirements = {
     *         { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID du filtre" }
     *     },
     *     statusCodes = {
     *         200 = "OK",
     *     }
     * )
     */
    public function deleteFilter($id)
    {
        $filter = $this->getFilterRepository()->find($id);
        $this->getDoctrine()->getManager()->remove($filter);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse (array("status" => 200));
    }
}
