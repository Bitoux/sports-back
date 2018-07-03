<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */

 class Invitation
 {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Many Invitations have One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invitations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * One Invitation has One Friend.
     * @ORM\OneToOne(targetEntity="Friend")
     * @ORM\JoinColumn(name="friend_id", referencedColumnName="id")
     */
    private $friend;

    /**
     * One Invitation has One event.
     * @ORM\OneToOne(targetEntity="Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    public function getUser(){
        return $this->user;
    }

    public function setUser(User $user){
        $user->addInvitation($this);
        $this->user = $user;
    }

    public function getFriend(){
        return $this->friend;
    }

    public function setFriend($friend){
        $this->friend = $friend;
    }

    public function getEvent(){
        return $this->event;
    }

    public function setEvent($event){
        $this->event = $event;
    }

    public function getEntity(){
        return $this->entity;
    }

    public function setEntity($entity){
        $this->entity = $entity;
    }
    
 }