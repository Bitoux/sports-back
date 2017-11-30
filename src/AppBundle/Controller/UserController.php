<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;



class UserController extends BaseController
{
    /**
     * @Rest\Get("/users", name="users_list")
     * @Rest\View
     *
     * @ApiDoc(
     *     section = "User CRUD",
     *     description = "Get the list of all users",
     *     statusCodes = {
     *         200 = "OK",
     *     }
     * )
     */
    public function getUsers()
    {
        $users = $this->getUserRepository()->findAll();

        return $users;
    }

    /**
     * @Rest\Get("/users/{email}/get", name="user_detail")
     * @Rest\View
     * @ApiDoc(
     *     section = "User CRUD",
     *     description = "Get the detail of one user",
     *     requirements = {
     *         { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID user" }
     *     },
     *     statusCodes = {
     *         200 = "OK",
     *     }
     * )
     */
    public function getUserByEmail($email)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByEmail($email);
        if(!$user instanceof User){
            return $this->error->elementNotFound();
        } else {
            return $user;
        }


    }

    /**
     * @Rest\Post("/register")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="Create" }
     *     }
     * )
     * @ApiDoc(
     *     section = "User CRUD",
     *     description = "Create a user in the database",
     *     requirements = {
     *         { "name"="firstName", "dataType"="string", "description"="ID du dossier" },
     *         { "name"="lastName", "dataType"="string", "description"="ID du dossier" },
     *         { "name"="userName", "dataType"="string", "description"="ID du dossier" },
     *         { "name"="password", "dataType"="string", "description"="ID du dossier" },
     *         { "name"="email", "dataType"="string", "description"="ID du dossier" },
     *         { "name"="adresse", "dataType"="string", "description"="ID du dossier" },
     *         { "name"="city", "dataType"="string", "description"="ID du dossier" },
     *         { "name"="country", "dataType"="string", "description"="ID du dossier" },
     *         { "name"="birthday", "dataType"="string", "description"="ID du dossier" }
     *     },
     *     statusCodes = {
     *         201 = "Created",
     *     }
     * )
     */
    public function createUser(User $user, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }


        $user->setRoles(array('ROLE_READER'));
        $user->setEnabled(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $userManager = $this->get('fos_user.user_manager');
        $user->setPlainPassword($user->getPassword());
        $userManager->updateUser($user);

        return $user;
    }

    /**
     * @Rest\Post("/users/edit")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="Create" }
     *     }
     * )
     * @ApiDoc(
     *     section = "User CRUD",
     *     description = "edit a user",
     *     requirements = {
     *         { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID du dossier" }
     *     },
     *     statusCodes = {
     *         200 = "OK",
     *     }
     * )
     */
    public function editUser(User $user, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $userManager = $this->get('fos_user.user_manager');
        $userRes = $this->getDoctrine()->getManager()->find('AppBundle:User', $user->getId());

        $userRes->setFirstName($user->getFirstName());
       /* $userRes->setLastName($user->getLastName());
        $userRes->setUserName($user->getUsername());
        $userRes->setEmail($user->getEmail());
        $userRes->setAdresse($user->getAdress());
        $userRes->setCity($user->getCity());
        $userRes->setCountry($user->getCountry());
        $userRes->setBirthday($user->getBirthday());
        $userRes->setPlainPassword($user->getPassword());*/

        $userManager->updateUser($userRes, false);
        $this->getDoctrine()->getManager()->flush();

        return $userRes;
    }

    /**
     * @Rest\Delete("/users/{id}/delete")
     * @Rest\View
     * @ApiDoc(
     *     section = "User CRUD",
     *     description = "delete a user",
     *     requirements = {
     *         { "name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID du dossier" }
     *     },
     *     statusCodes = {
     *         200 = "OK",
     *     }
     * )
     */
    public function deleteUser($id)
    {
        $user = $this->getUserRepository()->find($id);
        if (!$user instanceof User) {
            return $this->error->elementNotFound();
        }
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse (array("status" => 200));
    }
}
