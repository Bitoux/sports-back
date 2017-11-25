<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;
use JMS\Serializer\Annotation\Expose as Expose;


/**
 * @ORM\Entity()
 * @ORM\Table()
 *
 * @ExclusionPolicy("all")
 *
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $password;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $city;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $country;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @Assert\NotBlank(groups={"Create"})
     */
    private $birthday;

    /*  /**
       * @ORM\ManyToOne(targetEntity="Author", cascade={"all"}, fetch="EAGER")
       */
    /* private $author;*/

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }


}