<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class NotificationRepository extends EntityRepository
{
    public function setNotification(User $user, $lat, $lng)
    {
        $notification = $this->findOneBy(['user' => $user]);
        if (!$notification)
        {
            $notification = new Notification();
            $notification->setUser($user);
        }
        $notification->setLatitude($lat);
        $notification->setLongitude($lng);
        $notification->setIntervalNotify(1);
        $notification->setRadius(5);

        $em = $this->getEntityManager();
        $em->persist($notification);
        $em->flush();
        return 1;
    }

    public function getNotificationsAroundEvent($lat, $lng)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Notification::class, 'n')
        ->addFieldResult('n', 'id', 'id')
        ->addFieldResult('n', 'latitude', 'latitude')
        ->addFieldResult('n', 'longitude', 'longitude')
        ->addFieldResult('n', 'radius', 'radius')
        ->addFieldResult('n', 'intervalNotify', 'intervalNotify')
        ->addFieldResult('n', 'lastNotifyDate', 'lastNotifyDate');

        $query = $this->getEntityManager()->createNativeQuery("SELECT * FROM notifications n WHERE 111.111 * DEGREES(ACOS(COS(RADIANS(n.latitude)) * COS(RADIANS(:pointLatitude)) * COS(RADIANS(n.longitude - :pointLongitude)) + SIN(RADIANS(n.latitude)) * SIN(RADIANS(:pointLatitude)))) < n.radius", $rsm);
        return $query->setParameter(':pointLatitude', $lat)
              ->setParameter(':pointLongitude', $lng)
              ->getResult();
    }
}
