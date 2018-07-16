<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProEventRepository")
 */
class ProEvent
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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $hour;

    /**
      * Many Payments have One Company.
      * @ORM\ManyToOne(targetEntity="Company", inversedBy="proEvents")
      * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
      */
      private $company;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getAddress(){
        return $this->address;
    }

    public function setAddress($address){
        $this->address = $address;
    }

    public function getLat(){
        return $this->latitude;
    }

    public function setLat($lat){
        $this->latitude = $lat;
    }

    public function getLong(){
        return $this->longitude;
    }

    public function setLong($long){
        $this->longitude = $long;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDate($date){
        $this->date = $date;
    }

    public function getHour(){
        return $this->hour;
    }

    public function setHour($hour){
        $this->hour = $hour;
    }

    public function getCompany(){
        return $this->company;
    }

    public function setCompany($company){
        $company = $company->addProEvent($this);
        $this->company = $company;
    }

    
    


}