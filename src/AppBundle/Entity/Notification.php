<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 * @ORM\Table(name="notifications")
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", scale=10, precision=12)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale=10, precision=12)
     */
    protected $longitude;

    /**
     * @ORM\Column(type="integer")
     */
    protected $radius;

    /**
     * @ORM\Column(type="integer", name="intervalNotify")
     */
    protected $intervalNotify;

    /**
     * @ORM\Column(type="datetime", name="lastNotifyDate", nullable=true)
     */
    protected $lastNotifyDate;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

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
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Notifications
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
     * @return Notifications
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
     * Set radius
     *
     * @param integer $radius
     *
     * @return Notifications
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Get radius
     *
     * @return integer
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * Set lastNotifyDate
     *
     * @param \DateTime $lastNotifyDate
     *
     * @return Notifications
     */
    public function setLastNotifyDate($lastNotifyDate)
    {
        $this->lastNotifyDate = $lastNotifyDate;

        return $this;
    }

    /**
     * Get lastNotifyDate
     *
     * @return \DateTime
     */
    public function getLastNotifyDate()
    {
        return $this->lastNotifyDate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Notifications
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
     * Set intervalNotify
     *
     * @param integer $intervalNotify
     *
     * @return Notification
     */
    public function setIntervalNotify($intervalNotify)
    {
        $this->intervalNotify = $intervalNotify;

        return $this;
    }

    /**
     * Get intervalNotify
     *
     * @return integer
     */
    public function getIntervalNotify()
    {
        return $this->intervalNotify;
    }
}
