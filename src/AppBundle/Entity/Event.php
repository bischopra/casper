<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\Table(name="events")
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
     * @ORM\Column(type="string", length=192)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $alias;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $description;

    /**
     * @ORM\Column(type="datetime", name="eventDate")
     */
    protected $eventDate;

    /**
     * @ORM\Column(type="datetime", name="addDate", nullable=true)
     */
    protected $addDate;

    /**
     * @ORM\Column(type="integer")
     */
    protected $duration;

    /**
     * @ORM\Column(type="integer", name="maxGuestCount")
     */
    protected $maxGuestCount;

    /**
     * @ORM\Column(type="date", name="applyEndDate")
     */
    protected $applyEndDate;

    /**
     * @ORM\Column(type="integer")
     */
    protected $isPrivate;

    /**
     * @ORM\Column(type="decimal", scale=10, precision=12)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale=10, precision=12)
     */
    protected $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="event")
     */
    protected $participants;
    
    public function __construct()
    {
        $this->eventDate = new \DateTime;
        $this->applyEndDate = new \DateTime;
        $this->alias = '';
        $this->participants = new ArrayCollection;
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Event
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Event
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Event
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set eventDate
     *
     * @param \DateTime $eventDate
     *
     * @return Event
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Event
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set maxGuestCount
     *
     * @param integer $maxGuestCount
     *
     * @return Event
     */
    public function setMaxGuestCount($maxGuestCount)
    {
        $this->maxGuestCount = $maxGuestCount;

        return $this;
    }

    /**
     * Get maxGuestCount
     *
     * @return integer
     */
    public function getMaxGuestCount()
    {
        return $this->maxGuestCount;
    }

    /**
     * Set applyEndDate
     *
     * @param \DateTime $applyEndDate
     *
     * @return Event
     */
    public function setApplyEndDate($applyEndDate)
    {
        $this->applyEndDate = $applyEndDate;

        return $this;
    }

    /**
     * Get applyEndDate
     *
     * @return \DateTime
     */
    public function getApplyEndDate()
    {
        return $this->applyEndDate;
    }

    /**
     * Set isPrivate
     *
     * @param integer $isPrivate
     *
     * @return Event
     */
    public function setIsPrivate($isPrivate)
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    /**
     * Get isPrivate
     *
     * @return integer
     */
    public function getIsPrivate()
    {
        return $this->isPrivate;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Event
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Event
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Event
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Event
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Add participant
     *
     * @param \AppBundle\Entity\Participation $participant
     *
     * @return Event
     */
    public function addParticipant(\AppBundle\Entity\Participation $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * Remove participant
     *
     * @param \AppBundle\Entity\Participation $participant
     */
    public function removeParticipant(\AppBundle\Entity\Participation $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }
}
