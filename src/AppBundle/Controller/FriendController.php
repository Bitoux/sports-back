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

        $em = $this->getDoctrine()->getManager();

        $checkFriend = $this->getDoctrine()->getRepository(Friend::class)
        ->checkUsers([$adder, $added]);

        if($checkFriend){
            return 'Already in friend';
        }else{
            $newFriend = new Friend();
            $newFriend->setStatus($status);
            $newFriend->addUser($adder);
            $newFriend->addUser($added);
            
            // SEND MAIL TO SECOND USER
            $this->friendRequestEmail($this, $added->getEmail(), $adder);

            $em->persist($newFriend);
            $em->flush();

            return $newFriend->getUsers()[0];
        }
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