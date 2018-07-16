<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity
 */

 class Company
 {
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * One Customer has One User.
     * @ORM\OneToOne(targetEntity="User", mappedBy="company")
     */
     private $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastPay;

    /**
     * One Company has Many Payments.
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="company")
     */
     private $payments;

     /**
     * One Company has Many ProEvents.
     * @ORM\OneToMany(targetEntity="ProEvent", mappedBy="company")
     */
    private $proEvents;

    /**
     * One Company has Many Spot.
     * @ORM\OneToMany(targetEntity="Spot", mappedBy="company")
     */
    private $shops;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
 
     /**
      * @param mixed $id
      */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUser(){
        return $this->user;
    }

    public function setUser($user){
        $this->user = $user;
    }

    public function getLastPay(){
        return $this->lastPay;
    }

    public function setLastPay($lastPay){
        $this->lastPay = $lastPay;
    }

    public function getPayments(){
        return $this->payments;
    }

    public function setPayments($payments){
        $this->payments = $payments;
    }

    public function addPayment($payment){
        $this->payments[] = $payment;
        return $this;
    }

    public function getProEvents(){
        return $this->proEvents;
    }

    public function setProEvents($proEvents){
        $this->proEvents = $proEvents;
    }

    public function addProEvent($proEvent){
        $this->proEvents[] = $proEvent;
        return $this;
    }

    public function getShops(){
        return $this->shops;
    }

    public function setShops($shops){
        $this->shops = $shops;
    }

    public function addShop($shop){
        $this->shops[] = $shop;
        return $this;
    }


    public function __construct(){
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proEvents = new \Doctrine\Common\Collections\ArrayCollection();
    }
     

 }