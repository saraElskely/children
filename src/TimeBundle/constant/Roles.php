<?php

namespace TimeBundle\constant;

/**
 * Description of Roles
 *
 * @author saraelsayed
 */
class Roles {
    const ROLE_ADMIN = 1 ;
    const ROLE_MOTHER = 2 ;
    const ROLE_CHILD = 3 ;
    const ROLE_ARRAY = [
        self::ROLE_ADMIN,
        self::ROLE_MOTHER,
        self::ROLE_CHILD
    ];
}
