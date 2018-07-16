<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpotRepository")
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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isPro;

    /**
     * Many Spots have Many Filters.
     * @ORM\ManyToMany(targetEntity="Filter", cascade={"persist"})
     *
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
     * One Spots have Many Events.
     * @ORM\OneToMany(targetEntity="Event", mappedBy="spot", orphanRemoval=true, cascade={"all"})
		 * @ORM\JoinColumns({
		 *   @ORM\JoinColumn(name="spot_id", referencedColumnName="id", onDelete="CASCADE")
		 *	 })
     */
    protected $events;

    /**
      * Many Payments have One Company.
      * @ORM\ManyToOne(targetEntity="Company", inversedBy="shops")
      * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
      */
      private $company;

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
		 * @return mixed
     */
    public function addFilter(Filter $filters)
    {
        $this->filters[] = $filters;

        return $this;
    }

    /**
     * @param mixed $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @param mixed $filter
     * @return mixed
     */
    public function removeFilter(Filter $filter){
        $this->filters->removeElement($filter);

        return $this;
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
	 * @param mixed $event
	 */
    public function addEvent($event){
        $this->events[] = $event;
        $event->setSpot($this);
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

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getIsPro(){
        return $this->isPro;
    }

    public function setIsPro($isPro){
        $this->isPro = $isPro;
    }

    public function getCompany(){
        return $this->company;
    }

    public function setCompany($company){
        $company = $company->addShop($this);
        $this->company = $company;
    }

    /**
     * @param string $address
     * @param string $latitude
     * @param string $longitude
     * @param string $name
     * @param mixed $events
     * @param mixed $grades
     * @param mixed $filters
     *
     * @return mixed
     */
    public function setSpot($address, $latitude, $longitude, $name, $events, $filters){
        $this->address = $address;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->name = $name;
        $this->events = $events;
        $this->filters = $filters;

        return $this;
    }

    public function __construct()
    {
        $this->filters = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->grades = new ArrayCollection();
    }
		
    public function __toString()
    {
      return $this->name;
    }
  
}