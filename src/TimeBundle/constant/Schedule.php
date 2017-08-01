<?php

namespace TimeBundle\constant;

/**
 * Description of Schedule
 *
 * @author saraelsayed
 */
class Schedule {
    const SCHEDULE_DAILY = 0 ;
    const SCHEDULE_SUNDAY =  1;
    const SCHEDULE_MONDAY = 2;
    const SCHEDULE_TUESDAY = 4;
    const SCHEDULE_WEDNESDAY = 8;
    const SCHEDULE_THURSDAY = 16;
    const SCHEDULE_FRIDAY = 32;
    const SCHEDULE_SATURDAY = 64 ;
    const SCHEDULE_DAYS = [self::SCHEDULE_SUNDAY,
                            self::SCHEDULE_MONDAY,
                            self::SCHEDULE_TUESDAY,
                            self::SCHEDULE_WEDNESDAY,
                            self::SCHEDULE_THURSDAY,
                            self::SCHEDULE_FRIDAY,
                            self::SCHEDULE_SATURDAY
                            ] ;
    const SCHEDULE_DAYS_PER_WEEK = 7; 
}
