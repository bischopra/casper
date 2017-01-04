<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipationRepository")
 * @ORM\Table(name="participations", uniqueConstraints={@ORM\UniqueConstraint(name="u_user_event", columns={"user_id", "event_id"})})
 */
class Participation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="isInvitation")
     */
    protected $isInvitation;

    /**
     * @ORM\Column(type="datetime", name="invitationDate", nullable=true)
     */
    protected $invitationDate;

    /**
     * @ORM\Column(type="integer", name="isInvitationAccepted")
     */
    protected $isInvitationAccepted;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="participants")
     */
    protected $event;

    public function __construct()
    {
        $this->isInvitation = 0;
        $this->isInvitationAccepted = 0;
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
     * Set isInvitation
     *
     * @param integer $isInvitation
     *
     * @return Participation
     */
    public function setIsInvitation($isInvitation)
    {
        $this->isInvitation = $isInvitation;

        return $this;
    }

    /**
     * Get isInvitation
     *
     * @return integer
     */
    public function getIsInvitation()
    {
        return $this->isInvitation;
    }

    /**
     * Set isInvitationAccepted
     *
     * @param integer $isInvitationAccepted
     *
     * @return Participation
     */
    public function setIsInvitationAccepted($isInvitationAccepted)
    {
        $this->isInvitationAccepted = $isInvitationAccepted;

        return $this;
    }

    /**
     * Get isInvitationAccepted
     *
     * @return integer
     */
    public function getIsInvitationAccepted()
    {
        return $this->isInvitationAccepted;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Participation
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
     * Set event
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return Participation
     */
    public function setEvent(\AppBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set invitationDate
     *
     * @param \DateTime $invitationDate
     *
     * @return Participation
     */
    public function setInvitationDate($invitationDate)
    {
        $this->invitationDate = $invitationDate;

        return $this;
    }

    /**
     * Get invitationDate
     *
     * @return \DateTime
     */
    public function getInvitationDate()
    {
        return $this->invitationDate;
    }
}
