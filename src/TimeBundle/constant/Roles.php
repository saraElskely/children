<?php

namespace TimeBundle\constant;

/**
 * Description of Roles
 *
 * @author saraelsayed
 */
class Roles {
    const ROLE_ADMIN = 0 ;
    const ROLE_MOTHER = 1 ;
    const ROLE_CHILD = 2 ;
    const ROLE_ARRAY = [
        self::ROLE_ADMIN,
        self::ROLE_MOTHER,
        self::ROLE_CHILD
    ];
}
