<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Utils\DistanceUtils;

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
        $degDis = DistanceUtils::distance2degres($distance);
        $debug = ['degDis' => $degDis, 'lat' => $lat, 'lng' => $lng];
        $qr = $this->getEntityManager()->createQuery("SELECT e FROM AppBundle:Event e WHERE e.eventDate >= :now AND e.latitude <= :latitudeBound1 AND e.latitude >= :latitudeBound2 AND e.longitude <= :longitudeBound1 AND e.longitude >= :longitudeBound2")
                ->setParameter('now', new \DateTime)
                ->setParameter(':latitudeBound1', $lat + $degDis)
                ->setParameter(':latitudeBound2', $lat - $degDis)
                ->setParameter(':longitudeBound1', $lng + $degDis)
                ->setParameter(':longitudeBound2', $lng - $degDis)
                ->getResult();
        $result = [];
        foreach($qr as $event)
        {
            $result[] = [
                'name' => $event->getName(),
                'city' => $event->getCity(),
                'address' => $event->getAddress(),
                'lat' => $event->getLatitude(),
                'lng' => $event->getLongitude(),
            ];
        }
        return ['debug' => $debug, 'events' => $result];
    }
}
