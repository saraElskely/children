<?php

namespace TimeBundle\Utility;

use TimeBundle\constant\Schedule;

/**
 * Description of Date
 *
 * @author saraelsayed
 */
class Date {

    public static function getTodayInWeek() {
        $today = date('w', strtotime((new \DateTime())->format('Y-m-d')));
        return pow(2, $today);
    }

    public static function getDayInWeek($date) {
        return date('w', strtotime($date));
    }

    public static function getDayInWeekBasedOnStart($day, $offest) {
        return (($day >> 1) - $offest + Schedule::SCHEDULE_DAYS_PER_WEEK) % Schedule::SCHEDULE_DAYS_PER_WEEK;
    }

    public static function getStartDateInWeek($numOfWeek = 1) {
        $startDate = new \DateTime();
        return $startDate->modify("next friday -$numOfWeek week")->format('Y-m-d');
    }

    public static function getEmptyArrayOfDatesForWeek($startDate = null) {
        $arr = array();
        for ($i = 0; $i < Schedule::SCHEDULE_DAYS_PER_WEEK; $i++) {
            $date = is_null($startDate) ? new \DateTime() : $i === 0 ? new \DateTime($startDate) : $date->modify('+1 day');
            $arr[$date->format('Y-m-d')] = array();
//            $arr[(is_null($date) ? $date = new \DateTime() : $i === 0? $date : $date->modify('+1 day'))->format('Y-m-d')] = array();
        }
        return $arr;
    }
    
    public static function getStartDateInWeekBasedOnDate($date) {
        return (new \DateTime($date))->modify("next friday -1 week")->format('Y-m-d');
    }
    
    public static function getMaxNumOfWeekToChild( $endDate) {
        return (int)((new \DateTime())->diff((new \DateTime($endDate)))->format("%d")/7)+1;
    }

}
