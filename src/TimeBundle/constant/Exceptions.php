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
    const CODE_TASK_NOT_FOUND = 10 ;
    const CODE_PAGE_NUM_NOT_FOUND = 11;
    const CODE_ROLE_NOT_FOUND = 12;


    const EXCEPTION_MESSAGES = [
        self::CODE_ACCESS_DENIED => 'Access Denied',
        self::CODE_USER_NOT_FOUND => 'User Not Found',
        self::CODE_NOT_CHILD_USER => 'Not Child User',
        self::CODE_MOTHER_ID_NOT_FOUND => 'Mother Id Not Found ',
        self::CODE_MOTHER_HAS_NO_CHILDREN => 'Mother has no children',
        self::CODE_PAGE_NUM_NOT_FOUND => 'Page Number Not Found',
        self::CODE_ROLE_NOT_FOUND => 'Role Not Found'
        
    ];
    
    const EXCEPTION_STATUS_CODE = [
        self::CODE_ACCESS_DENIED => 501,
        self::CODE_USER_NOT_FOUND => 404,
        self::CODE_NOT_CHILD_USER => 404,
        self::CODE_MOTHER_ID_NOT_FOUND => 500,
        self::CODE_PAGE_NUM_NOT_FOUND => 500,
        self::CODE_ROLE_NOT_FOUND => 500,
    ];
    
}
