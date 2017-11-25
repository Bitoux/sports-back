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
     * @Rest\Get("/users/{id}", name="user_detail")
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
    public function getArticle($id)
    {
        $user = $this->getUserRepository()->find($id);
        if(!$user instanceof User){
            return $this->error->elementNotFound();
        } else { return $user; }

    }

    /**
     * @Rest\Post("/users/create")
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

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Rest\Put("/users/{id}/edit")
     * @Rest\View
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
    public function editUser($id, Request $request)
    {
        $data = json_decode($request->getContent());
        $user = $this->getUserRepository()->find($id);
        if (!$user instanceof User) {
            return $this->error->elementNotFound();
        }

        $user->setFirstName($data->first_name);
        $user->setLastName($data->last_name);
        $user->setUserName($data->user_name);
        $user->setPassword($data->password);
        $user->setEmail($data->email);
        $user->setAdresse($data->adresse);
        $user->setCity($data->city);
        $user->setCountry($data->country);
        $user->setBirthday($data->birthday);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $user;
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
    public function deleteArticle($id)
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
