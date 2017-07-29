<?php

namespace TimeBundle\Utility;

/**
 * Description of Date
 *
 * @author saraelsayed
 */
class Date {
    
    public static function getTodayInWeek()
    {
        $today = new \DateTime();
        $today = strtotime($today->format('y-m-d'));
        $today = date('w', $today);
        
        return pow(2,$today);
    }
}
