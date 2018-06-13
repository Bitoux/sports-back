<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Map;
use AppBundle\Entity\User;
use AppBundle\Entity\Filter;
use Doctrine\Common\Collections\ArrayCollection;
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
     */
    public function getUsers()
    {
        $users = $this->getUserRepository()->findAll();

        return $users;
    }

    /**
     * @Rest\Get("/users/{username}/get", name="user_detail")
     * @Rest\View
     */
    public function getUserByUsername($username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        if(!$user instanceof User){
            return $this->error->elementNotFound();
        } else {
            return $user;
        }
    }

	/**
	 * @Rest\Get("/users/{id}/get", name="user_detail_id")
	 * @Rest\View
	 */
	public function getUserById($idUser){
		$userManager = $this->get('fos_user.user_manager');
		$user = $userManager->findUserBy(array('id'=>$idUser));
		if(!$user instanceof User){
			return $idUser;
		} else {
			return $user;
		}
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

        $map = new Map();
        $map->setName($user->getUsername());

        $user->setRoles(array('ROLE_READER'));
        $user->setEnabled(true);
        $user -> setMap($map);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $userManager = $this->get('fos_user.user_manager');
        $user->setPlainPassword($user->getPassword());
        $userManager->updateUser($user);

        return $user;
    }

    /**
     * @Rest\Put("/users/{id}/edit")
     * @Rest\View(StatusCode = 200)
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="Create" }
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
       $userRes = $userManager->findUserByUsername($user->getUsername());
       $userRes->setFirstName($user->getFirstName());
       $userRes->setLastName($user->getLastName());
       $userRes->setUserName($user->getUsername());
       $userRes->setEmail($user->getEmail());
       $userRes->setAdress($user->getAdress());
       $userRes->setCity($user->getCity());
       $userRes->setCountry($user->getCountry());
       /*$userRes->setBirthday($user->getBirthday());*/

       $userManager->updateUser($userRes, false);
       $this->getDoctrine()->getManager()->flush();

       return $userRes;
    }

    /**
     * @Rest\Delete("/users/{id}/delete")
     * @Rest\View
     */
    public function deleteUser($idUser)
    {
        $user = $this->getUserRepository()->find($idUser);
        if (!$user instanceof User) {
            return $this->error->elementNotFound();
        }
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse (array("status" => 200));
    }


    /**
     * @Rest\Post("/users/{id}/filters")
     * @Rest\View(StatusCode = 200)
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="Create" }
     *     }
     * )
     */
    public function editUserFilters(User $user, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }

        $userManager = $this->get('fos_user.user_manager');
        $userRes = $userManager->findUserByUsername($user->getUsername());
        $filters = array();

        foreach($user->getFilters() as $filter){
            $tmpFilter = $this->getFilterRepository()->find($filter->getId());
            array_push($filters, $tmpFilter);
        }
        $userRes->setFilters($filters);

        $userManager->updateUser($userRes);
        $this->getDoctrine()->getManager()->flush();

        return $userRes;
    }

    /**
     * @Rest\Post("/users/{id}/maps")
     * @Rest\View(StatusCode = 200)
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="Create" }
     *     }
     * )
     */
    public function shareMaps(User $user, ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }

        $userManager = $this->get('fos_user.user_manager');
        $user1 = $userManager->findUserByEmail($user->getUsername());
        if($user1){
            $user2 = $userManager->findUserBy(array('id'=>$user->getId()));
            $map = $user1->getMap();
            $mapsRes = new ArrayCollection();
            $mapsRes->add($map);

            foreach($user2->getMaps() as $m){
                $mapsRes->add($m);
            }

            $user2->setMaps($mapsRes);

            $userManager->updateUser($user2);
            $this->getDoctrine()->getManager()->flush();

            return $user2;

        } else {
            return $user;
        }

    }

}
