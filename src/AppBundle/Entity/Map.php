<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity

 */
class Map
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
    protected $name;

    /**
     * Many Maps have Many Spots.
     * @ORM\ManyToMany(targetEntity="Spot", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="map_spots",
     *      joinColumns={@ORM\JoinColumn(name="map_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="spot_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    protected $spots;

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
    public function getSpots()
    {
        return $this->spots;
    }

	/**
	 * @param mixed $spots
	 */
	public function setSpots($spots){
		$this->spots = $spots;
	}

    /**
     * @param mixed $spots
		 * @return mixed
     */
    public function addSpot(Spot $spots)
    {
        $this->spots[] = $spots;

        return $this;
    }

		/**
		 * @param mixed $spot
		 * @return mixed
		 */
		public function removeSpot(Spot $spot){
			$this->spots->removeElement($spot);

			return $this;
		}


    public function __construct()
		{
			$this->spots = new ArrayCollection();
		}


}