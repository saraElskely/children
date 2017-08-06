<?php

namespace TimeBundle\Twig;
use TimeBundle\constant\Schedule;
/**
 * Description of TimeExtension
 *
 * @author saraelsayed
 */
class TimeExtension extends \Twig_Extension
{
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('scheduleDate', array($this, 'scheduleFilter')),
        );
    }
    
    public function scheduleFilter($schedule){
        $arr = array();
        foreach (Schedule::SCHEDULE_DAYS as $key => $day) {
            if($day & $schedule)
                $arr[]= Schedule::SCHEDULE_WEEK_DAYS[$key];
        }
        return $arr;
    }
    
}
