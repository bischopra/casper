<?php

namespace AppBundle\Utils;

class DistanceUtils
{
    public static $degInKm = 111.13384;# 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
    public static $degInMi = 69.05482;# 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
    public static $degInNmi = 59.97662;# 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)

    public static function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2)
    {
        // Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

        // Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        switch($unit)
        {
            case 'km':
                $distance = $degrees * DistanceUtils::$degInKm; 
                break;
            case 'mi':
                $distance = $degrees * DistanceUtils::$degInMi;
                break;
            case 'nmi':
                $distance =  $degrees * DistanceUtils::$degInNmi;
        }
        return round($distance, $decimals);
    }

    public static function distance2degres($distance, $decimals = 10)
    {
        return round($distance / DistanceUtils::$degInKm, $decimals);
    }
}
