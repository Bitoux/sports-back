<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */

 class Payment
 {
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
     private $date;

     /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
     private $amount;

     /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
     private $orderId;

     /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
     private $payerId;

     /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
     private $paymentId;

     /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
     private $paymentToken;

     /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
     private $returnUrl;

     /**
      * Many Payments have One Company.
      * @ORM\ManyToOne(targetEntity="Company", inversedBy="payments")
      * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
      */
     private $company;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDate($date){
        $this->date = $date;
    }

    public function getAmount(){
        return $this->amount;
    }

    public function setAmount($amount){
        $this->amount = $amount;
    }

    public function getCompany(){
        return $this->company;
    }

    public function setCompany($company){
        $company = $company->addPayment($this);
        $this->company = $company;
    }

    public function getOrderId(){
        return $this->orderId;
    }

    public function setOrderId($orderId){
        $this->orderId = $orderId;
    }

    public function getPayerId(){
        return $this->payerId;
    }

    public function setPayerId($payerId){
        $this->payerId = $payerId;
    }

    public function getPaymentId(){
        return $this->paymentId;
    }

    public function setPaymentId($paymentId){
        $this->paymentId = $paymentId;
    }

    public function getPaymentToken(){
        return $this->paymentToken;
    }

    public function setPaymentToken($paymentToken){
        $this->paymentToken = $paymentToken;
    }

    public function getReturnUrl(){
        return $this->returnUrl;
    }

    public function setReturnUrl($returnUrl){
        $this->returnUrl = $returnUrl;
    }

    public function __construct(){
        
    }



 }