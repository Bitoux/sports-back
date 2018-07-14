<?php

namespace AppBundle\Controller;

use AppBundle\Repository\FriendRepository;
use AppBundle\Entity\Friend;
use AppBundle\Entity\User;
use AppBundle\Entity\Invitation;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;

class FriendController extends BaseController{

    /**
     * @Rest\Post("/friends/accept", name="accept_friend")
     * @Rest\View(StatusCode = 200)
     */
    public function acceptFriend(Request $request){
        $invitationId = $request->get('id');
        $accept = $request->get('accept');

        $invitation = $this->getInvitationRepository()->find($invitationId);

        $friend = $invitation->getFriend();

        if($accept){
            $friend->setStatus('accept');
            $this->getDoctrine()->getManager()->remove($invitation);
            $this->getDoctrine()->getManager()->persist($friend);
        }else{
            $this->getDoctrine()->getManager()->remove($invitation);
            $this->getDoctrine()->getManager()->remove($friend);
        }

        
        $this->getDoctrine()->getManager()->flush();
        
        return "OK";
    }

    /**
     * @Rest\Post("/friends/acceptEvent", name="accept_friendEvent")
     * @Rest\View(StatusCode = 200)
     */
    public function acceptEvent(Request $request){
        $invitationId = $request->get('id');
        $accept = $request->get('accept');
        $invitation = $this->getInvitationRepository()->find($invitationId);
        $event = $invitation->getEvent();
        $eventRes = $this->getEventRepository()->find($event->getId());
        $users = $eventRes->getUsers();
        $tmpUser = $this->getUserRepository()->find($invitation->getUser()->getId());
        $users->add($tmpUser);
        if($accept){
            $eventRes->setUsers($users);
            $this->getDoctrine()->getManager()->remove($invitation);
            $this->getDoctrine()->getManager()->persist($eventRes);
        }else{
            $this->getDoctrine()->getManager()->remove($invitation);
        }

        $this->getDoctrine()->getManager()->flush();

        $new = $this->getUserRepository()->find($tmpUser->getId());
        $userManager = $this->get('fos_user.user_manager');
        $ur = $userManager->findUserByUsername($new->getUserName());
        return $ur;
    }

    /**
     * @Rest\Post("/friends/request", name="friend_request")
     * @Rest\View(StatusCode = 200)
     */
    public function addFriendRequest(Request $request ){
        /*if (count($violations)) {
			$message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
			foreach ($violations as $violation) {
				$message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
			}
			throw new ResourceValidationException($message);
        }*/

        $status =  $request->get('friend_status');
        $adder = $this->getUserRepository()->find($request->get('id'));

        $userManager = $this->get('fos_user.user_manager');
        $added = $userManager->findUserByUsername($request->get('username'));

        if(is_null($added)){
            return 'User not found';
        }
        
        $em = $this->getDoctrine()->getManager();

        $newFriend = new Friend();
        $newFriend->setStatus($status);
        $newFriend->addUser($adder);
        $newFriend->addUser($added);

        // SEND MAIL TO SECOND USER
        $this->friendRequestEmail($this, $added->getEmail(), $adder);

        $invitation = new Invitation();
        $invitation->setFriend($newFriend);
        $invitation->setUser($added);

        $em->persist($newFriend);
        $em->persist($invitation);
        $em->flush();

        $new = $this->getUserRepository()->find($request->get('id'));
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($new->getUserName());
        return $user;

    }

    /**
     * @Rest\Post("/friends/requestEvent", name="friend_requestEvent")
     * @Rest\View(StatusCode = 200)
     */
    public function addFriendRequestEvent(Request $request){
        /*if (count($violations)) {
			$message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
			foreach ($violations as $violation) {
				$message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
			}
			throw new ResourceValidationException($message);
        }*/

        foreach($request->get('users') as $user) {
            $tmpUser = $this->getUserRepository()->find($user['id']);
            $event = $this->getEventRepository()->find($request->get('id_event'));

            if(is_null($tmpUser)){
                return 'User not found';
            }

            $em = $this->getDoctrine()->getManager();
            $invitation = new Invitation();
            $invitation->setEvent($event);
            $invitation->setUser($tmpUser);

            $em->persist($invitation);
            $em->flush();
        }

        $new = $this->getUserRepository()->find($request->get('id'));
        $userManager = $this->get('fos_user.user_manager');
        $usr = $userManager->findUserByUsername($new->getUserName());
        return $usr;

    }

    public function friendRequestEmail($controller,$sendTo, $userRequest){
        $message = (new \Swift_Message('Sports \\ Friend Request'))
        ->setFrom('sportsesgi@gmail.com')
        ->setTo($sendTo)
        ->setBody(
            $controller->renderView(
                // app/Resources/views/Emails/registration.html.twig
                'Emails/friendRequest.html.twig',
                array('name' => $userRequest->getUsername())
            ),
            'text/html'
        );
        $this->get('mailer')->send($message);
    }
}