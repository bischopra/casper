<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use AppBundle\Entity\Event;

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
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Event::class, 'e')
        ->addFieldResult('e', 'id', 'id')
        ->addFieldResult('e', 'name', 'name')
        ->addFieldResult('e', 'city', 'city')
        ->addFieldResult('e', 'address', 'address')
        ->addFieldResult('e', 'description', 'description')
        ->addFieldResult('e', 'eventDate', 'eventDate')
        ->addFieldResult('e', 'duration', 'duration')
        ->addFieldResult('e', 'maxGuestCount', 'maxGuestCount')
        ->addFieldResult('e', 'applyEndDate', 'applyEndDate')
        ->addFieldResult('e', 'is_private', 'isPrivate')
        ->addFieldResult('e', 'latitude', 'latitude')
        ->addFieldResult('e', 'longitude', 'longitude')
        ->addFieldResult('e', 'alias', 'alias');

        $query = $this->getEntityManager()->createNativeQuery("SELECT * FROM events e WHERE e.eventDate >= :now AND 111.111 * DEGREES(ACOS(COS(RADIANS(e.latitude)) * COS(RADIANS(:pointLatitude)) * COS(RADIANS(e.longitude - :pointLongitude)) + SIN(RADIANS(e.latitude)) * SIN(RADIANS(:pointLatitude)))) < :distance", $rsm);
        return $query->setParameter('now', new \DateTime)
              ->setParameter(':pointLatitude', $lat)
              ->setParameter(':pointLongitude', $lng)
              ->setParameter(':distance', $distance)
              ->getResult();
    }
}
