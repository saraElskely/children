<?php

namespace TimeBundle\constant;

/**
 * Description of Exceptions
 *
 * @author saraelsayed
 */
class Exceptions {
    const CODE_ACCESS_DENIED = 1;
    const CODE_USER_NOT_FOUND = 2 ;
    const CODE_NOT_CHILD_USER = 3;
    const CODE_MOTHER_ID_NOT_FOUND = 4;
    const CODE_MOTHER_HAS_NO_CHILDREN = 5;
    const CODE_ADMIN_NOT_FOUND = 6 ;
    const CODE_TASK_NOT_FOUND = 10 ;
    const CODE_PAGE_NUM_NOT_FOUND = 11;
    const CODE_ROLE_NOT_FOUND = 12;
    const CODE_SCHEDULE_NOT_FOUND = 13;


    const EXCEPTION_MESSAGES = [
        self::CODE_ACCESS_DENIED => 'Access Denied',
        self::CODE_USER_NOT_FOUND => 'User Not Found',
        self::CODE_NOT_CHILD_USER => 'Not Child User',
        self::CODE_MOTHER_ID_NOT_FOUND => 'Mother Id Not Found ',
        self::CODE_MOTHER_HAS_NO_CHILDREN => 'Mother has no children',
        self::CODE_ADMIN_NOT_FOUND => 'Admin not found',
        self::CODE_PAGE_NUM_NOT_FOUND => 'Page Number Not Found',
        self::CODE_ROLE_NOT_FOUND => 'Role Not Found',
        self::CODE_SCHEDULE_NOT_FOUND => 'Schedule Not Found ',
                
        
    ];
    
    const EXCEPTION_STATUS_CODE = [
        self::CODE_ACCESS_DENIED => 401,
        self::CODE_USER_NOT_FOUND => 401,
        self::CODE_NOT_CHILD_USER => 401,
        self::CODE_MOTHER_ID_NOT_FOUND => 402,
        self::CODE_PAGE_NUM_NOT_FOUND => 402,
        self::CODE_ROLE_NOT_FOUND => 402,
        self::CODE_SCHEDULE_NOT_FOUND => 402 ,
        self::CODE_ADMIN_NOT_FOUND => 500,
    ];
    
}
