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

    public function getNearbyEvents($lat, $lng, $distance)
    {
        return $this->getEntityManager()->createQuery("SELECT e FROM AppBundle:Event e WHERE e.eventDate >= :now AND e.latitude <= :latitudeBound1 AND e.latitude >= :latitudeBound2 AND e.longitude <= :longitudeBound1 AND e.longitude >= :longitudeBound2")
                ->setParameter('now', new \DateTime)
                ->setParameter(':latitudeBound1', $lat + $distance)
                ->setParameter(':latitudeBound2', $lat - $distance)
                ->setParameter(':longitudeBound1', $lng + $distance)
                ->setParameter(':longitudeBound2', $lng - $distance)
                ->getResult();
    }
}
