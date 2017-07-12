<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Event;

class EventService
{
    protected $_degInKm = 111.13384;# 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
    protected $_degInMi = 69.05482;# 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
    protected $_degInNmi = 59.97662;# 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getNearbyEvents($lat, $lng, $distance = 5)
    {
        $degDis = $this->distance2degres($distance);
        $debug = ['degDis' => $degDis, 'lat' => $lat, 'lng' => $lng];
        $qr = $this->em->getRepository(Event::class)->getNearbyEvents($lat, $lng, $distance);
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

    public function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2)
    {
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

        switch($unit)
        {
            case 'km':
                $distance = $degrees * $this->_degInKm; 
                break;
            case 'mi':
                $distance = $degrees * $this->_degInMi;
                break;
            case 'nmi':
                $distance =  $degrees * $this->_degInNmi;
        }
        return round($distance, $decimals);
    }

    public function distance2degres($distance, $decimals = 10)
    {
        return round($distance / $this->_degInKm, $decimals);
    }
}
