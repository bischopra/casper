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
}
