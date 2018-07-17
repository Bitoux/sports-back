<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
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
     * One Event have one Owner
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $hour;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $time;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $price;

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
     * Many Event have Many Users.
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="events_users",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")}
     *      )
     */
    protected $users;

    /**
     * Many Spots have Many Grades.
     * @ORM\ManyToMany(targetEntity="Filter")
     * @ORM\JoinTable(name="events_filters",
     *      joinColumns={@ORM\JoinColumn(name="filter_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")}
     *      )
     */
    protected $filters;

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
     * @param Spot $spot
     * @return $this
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;

        return $this;
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
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param mixed $hour
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
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
        $spot = $spot->addEvent($this);
    	$this->spot = $spot;

    	return $this;
	}

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param mixed $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

	/**
	 * @param $owner
	 * @param $date
	 * @param $hour
	 * @param $time
	 * @param $level
	 * @param $price
	 * @param $description
	 * @param $nb_user
	 * @return $this
	 */
		public function setEvent($date, $hour, $time, $level, $price, $description, $nb_user){
			$this->date = $date;
			$this->hour = $hour;
			$this->time = $time;
			$this->level = $level;
			$this->price = $price;
			$this->description = $description;
			$this->nb_user = $nb_user;

			return $this;
		}



  
  
}