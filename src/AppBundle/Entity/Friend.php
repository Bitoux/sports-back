<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity
 * 
 */
class Friend
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="friends")
     */
    protected $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $friendStatus;

    /**
     * @return integer
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id){
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUsers(){
        return $this->users;
    }

    /**
     * @param User
     */
    public function setUsers($users){
        $this->users = $users;
    }

    /**
     * @return string
     */
    public function getStatus(){
        return $this->friendStatus;
    }

    /**
     * @param string
     */
    public function setStatus($status){
        $this->friendStatus = $status;
    }

    /**
     * @param User
     */
    public function addUser($user){
        $this->users->add($user);
    }


    public function __contruct(){
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
}