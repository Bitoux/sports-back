<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity

 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $subject;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    protected $nb_user;

    /**
     * Many Event have One Spot
     * @ORM\ManyToOne(targetEntity="Spot", inversedBy="events")
     * @ORM\JoinColumn(name="spot_id", referencedColumnName="id")
     */
    protected $spot;

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

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getNbUser()
    {
        return $this->nb_user;
    }

    /**
     * @param mixed $nb_user
     */
    public function setNbUser($nb_user)
    {
        $this->nb_user = $nb_user;
    }

	/**
	 * @return mixed
	 */
    public function getSpot(){
    	return $this->spot;
		}

	/**
	 * @param Spot $spot
	 * @return $this
	 */
		public function setSpot(Spot $spot){
    	$this->spot = $spot;

    	return $this;
		}

	/**
	 * @param $name
	 * @param $date
	 * @param $subject
	 * @param $description
	 * @return $this
	 */
		public function setEvent($name, $date, $subject, $description, $nb_user){
			$this->name = $name;
			$this->date = $date;
			$this->subject = $subject;
			$this->description = $description;
			$this->nb_user = $nb_user;

			return $this;
		}




}