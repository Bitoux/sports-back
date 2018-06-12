<?php

namespace AppBundle\Controller;

use AppBundle\Repository\FriendRepository;
use AppBundle\Entity\Friend;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;

class FriendController extends BaseController{

    /**
     * @Rest\Post("/friends/request", name="friend_request")
     * @Rest\View(StatusCode = 200)
     * @ParamConverter("friend", converter="fos_rest.request_body")
     */
    public function addFriendRequest(Friend $friend, ConstraintViolationList $violations ){
        if (count($violations)) {
			$message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
			foreach ($violations as $violation) {
				$message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
			}
			throw new ResourceValidationException($message);
        }

        $em = $this->getDoctrine()->getManager();

        $friendRepository = new FriendRepository($em, Friend::class);

        if($friendRepository->checkUsers($friend->getUsers())){
            return 'Already in friend';
        }else{
            $newFriend = new Friend();
            $newFriend->setStatus($friend->getStatus);
            foreach($friend->getUsers() as $user){
                $newFriend->addUser($user);
            }

            $em->persist($friend);
            $em->flush();

            return $friend;
        }
    }
}