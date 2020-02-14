<?php


namespace App\Service;

//Store the possible duration type (hour, day, week...) for an event

class DurationUnit
{

    public static function hour(){
        return 'heure';
    }
    public static function day(){
        return 'jour';
    }
    public static function week(){
        return 'semaine';
    }
    public static function month(){
        return 'mois';
    }

    public static function convertDurationIntoHours($value, $durationUnit ){

        switch ($durationUnit){
            case DurationUnit::hour():
                return $value;
                break;
            case DurationUnit::day():
                return $value * 24 ;
                break;
            case DurationUnit::week():
                return $value * 24 * 7;
                break;
            case DurationUnit::month():
                return $value * 24 * 30.5;
            default:
                return $value;
        }
    }

}