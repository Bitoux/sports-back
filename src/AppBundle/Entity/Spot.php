<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity

 */
class Spot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $latitude;

		/**
		 * @ORM\Column(type="string", length=255, nullable=true)
		 */
		protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * Many Spots have Many Filters.
     * @ORM\ManyToMany(targetEntity="Filter")
     * @ORM\JoinTable(name="spots_filters",
     *      joinColumns={@ORM\JoinColumn(name="spot_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="filter_id", referencedColumnName="id")}
     *      )
     */
    protected $filters;


    /**
     * Many Spots have Many Grades.
     * @ORM\ManyToMany(targetEntity="Grade")
     * @ORM\JoinTable(name="spots_grades",
     *      joinColumns={@ORM\JoinColumn(name="spot_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="grade_id", referencedColumnName="id")}
     *      )
     */
    protected $grades;

    /**
     * Many Spots have Many Events.
     * @ORM\ManyToMany(targetEntity="Event")
     * @ORM\JoinTable(name="spots_events",
     *      joinColumns={@ORM\JoinColumn(name="spot_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")}
     *      )
     */
    protected $events;

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
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
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
     * @return mixed
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @param mixed $grades
     */
    public function setGrades($grades)
    {
        $this->grades = $grades;
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param mixed $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
		 * @return string
		 */
    public function getAddress(){
    	return $this->address;
		}

		/**
		 * @param string $address
		 * @return string
		 */
		public function setAddress($address){
			$this->address = $address;
			return $this->address;
		}

}