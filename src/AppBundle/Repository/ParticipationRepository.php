<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Participation;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;

class ParticipationRepository extends EntityRepository
{
    public function getUserAttendingEvents(User $user)
    {
        return $this->getEntityManager()->createQueryBuilder()
                ->select('p, e')
                ->from('AppBundle:Participation', 'p')
                ->join('p.event', 'e')
                ->where('p.user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getArrayResult();
    }

    public function getParticipation(User $user, Event $event)
    {
        return $this->findOneBy(['user' => $user, 'event' => $event]);
    }

    public function attend(Event $event, User $user)
    {
        $participation = $this->getParticipation($user, $event);
        if (!$participation)
        {
            $participation = new Participation;
            $participation->setEvent($event);
            $participation->setUser($user);
        }

        if ($participation->getIsInvitation())
        {
            $participation->setIsInvitationAccepted(1);
        }

        $em = $this->getEntityManager();
        $em->persist($participation);
        $em->flush();
        return 1;
    }

    public function remove(Event $event, User $user)
    {
        $participation = $this->getParticipation($user, $event);
        $em = $this->getEntityManager();
        $em->remove($participation);
        $em->flush();
        return 1;
    }

    public function setInvitationAsSent(Event $event, User $user)
    {
        $participation = $this->getParticipation($user, $event);
        if (!$participation)
        {
            $participation = new Participation;
            $participation->setEvent($event);
            $participation->setUser($user);
        }
        $participation->setIsInvitation(1);
        $participation->setInvitationDate(new \DateTime());

        $em = $this->getEntityManager();
        $em->persist($participation);
        $em->flush();
        return 1;
    }

    public function getParticipatingUsers($idEvent)
    {
        return $this->getEntityManager()->createQueryBuilder()
                ->select('p')
                ->from('AppBundle:Participation', 'p')
                ->where('p.event = :id AND (p.isInvitation = 0 OR (p.isInvitation = 1 AND p.isInvitationAccepted = 1))')
                ->setParameter('id', $idEvent)
                ->getQuery()
                ->getResult();
    }
}
