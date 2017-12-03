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


class User_FilterController extends BaseController
{
    /**
     * @Rest\Get("/users/{username}/filters", name="user_filter_list")
     * @Rest\View
     *
     * @ApiDoc(
     *     section = "User Filter",
     *     description = "Get the list of all filters",
     *     statusCodes = {
     *         200 = "OK",
     *     }
     * )
     */
    public function getUserFilter($username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        if(!$user instanceof User){
            return $this->error->elementNotFound();
        } else {
            $filters = $this->getUserFilterRepository()->findBy( ['id_user' => $user->getId()]);
            return $filters;
        }
    }



}
