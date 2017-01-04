<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function getPublicEvents()
    {
        return $this->getEntityManager()->createQueryBuilder()
                ->select('e')
                ->from('AppBundle:Event', 'e')
                ->where('e.eventDate >= :now AND e.isPrivate = 0')
                ->setParameter('now', new \DateTime)
                ->orderBy('e.eventDate', 'asc')
                ->getQuery()
                ->getArrayResult();
    }

    public function getUserEvents($user)
    {
        return $this->getEntityManager()->createQueryBuilder()
                ->select('e')
                ->from('AppBundle:Event', 'e')
                ->where('e.user = :user')
                ->setParameter('user', $user)
                ->orderBy('e.eventDate', 'asc')
                ->getQuery()
                ->getArrayResult();
    }
}
