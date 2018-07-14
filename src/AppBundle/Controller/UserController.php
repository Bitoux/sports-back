<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Map;
use AppBundle\Entity\User;
use AppBundle\Entity\Filter;
use AppBundle\Entity\Company;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;



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
	 * @Rest\Get("/users/{id}/friends", name="user_friends")
	 * @Rest\View
	 */
	public function getFriendsByUserId($id){
		$userManager = $this->get('fos_user.user_manager');
		$user = $userManager->findUserBy(array('id'=>$id));
		if(!$user instanceof User){
			return $id;
		} else {
			return $user->getFriends();
		}
    }
    
    /**
	 * @Rest\Get("/users/{id}/invitations", name="user_invitations")
	 * @Rest\View
	 */
	public function getInvitationsByUserId($id){
		$userManager = $this->get('fos_user.user_manager');
		$user = $userManager->findUserBy(array('id'=>$id));
		if(!$user instanceof User){
			return $id;
		} else {
			return $user->getInvitations();
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
        $user->setMap($map);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $userManager = $this->get('fos_user.user_manager');
        $user->setPlainPassword($user->getPassword());
        $password = $user->getPassword();
        $userManager->updateUser($user);

        $this->friendRequestEmail($this, $user, $password);

        return $user;
    }

    /**
	 * @Rest\Post("/user/company/edit", name="user_edit_company")
	 * @Rest\View(StatusCode = 200)
	 *
	 */
    public function editUserCompany(Request $request){
        $id = $request->get('id');
        $username = $request->get('username');
        $email = $request->get('email');
        $address = $request->get('address');
        $city = $request->get('city');
        $country = $request->get('country');
        $changed = $request->get('changed');
        $img = $request->files->get('picture');
        $changedPin = $request->get('changedPin');
        $pin = $request->files->get('pinMap');

        $user = $this->getUserRepository()->find($id);

        $user->setUserName($username);
        $user->setEmail($email);
        $user->setAdress($address);
        $user->setCity($city);
        $user->setCountry($country);

        if($changed){
            $fileName = $user->getUsername() . '-profil.' . $img->guessExtension();

            $img->move(
                $this->getParameter('company_directory'),
                $fileName
            );
            $user->setPicture($fileName);
        }

        if($changedPin){
            $fileName = $user->getUsername() . '-pin.' . $pin->guessExtension();

            $pin->move(
                $this->getParameter('company_directory'),
                $fileName
            );
            $user->setPinMap($fileName);
        }

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $user;
    }

    /**
     * @Rest\Post("/users/create/company")
     * @Rest\View(StatusCode = 201)
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "validator"={ "groups"="Create" }
     *     }
     * )
     */
     public function createUserCompany(User $user, ConstraintViolationList $violations){
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
        $user->setMap($map);

        $company = new Company();
        $company->setUser($user);
        $user->setCompany($company);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->persist($company);
        $entityManager->flush();

        $userManager = $this->get('fos_user.user_manager');
        $user->setPlainPassword($user->getPassword());
        $password = $user->getPassword();
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

    /**
     * @Rest\Post("/users/edit/image", name="edit_image")
     * @Rest\View(StatusCode = 200)
     */
    public function editImage(Request $request, ConstraintViolationList $violations)
    {

        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }

        return "OK";
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id'=>$request->get('id')));
        
        return $user;

        $imgBase64 = $request->get('img');

        $data = str_replace('data:image/*;charset=utf-8;base64,', '', $imgBase64);
        $data = str_replace(' ', '+', $data);

        $data = base64_decode($data);
        $image = imagecreatefromstring($data);
        $filename = __DIR__ . '../../../web/uploads/user-' . $user->getId() . '.png';
        $saveImage = imagejpeg($image, $filename);

        return 'OK';
    }

    // SEND EMAIL ON REGISTER
    public function friendRequestEmail($controller,$user, $password){
        $message = (new \Swift_Message('Sports \\ Registration'))
        ->setFrom('sportsesgi@gmail.com')
        ->setTo($user->getEmail())
        ->setBody(
            $controller->renderView(
                // app/Resources/views/Emails/registration.html.twig
                'Emails/registration.html.twig',
                array(
                    'user' => $user,
                    'password' => $password
                )
            ),
            'text/html'
        );
        $this->get('mailer')->send($message);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
