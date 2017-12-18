<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


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
     * @ORM\ManyToMany(targetEntity="Spot", cascade={"persist"})
     * @ORM\JoinTable(name="map_spots",
     *      joinColumns={@ORM\JoinColumn(name="map_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="spot_id", referencedColumnName="id")}
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
    public function setSpots($spots)
    {
        $this->spots = $spots;
    }




}