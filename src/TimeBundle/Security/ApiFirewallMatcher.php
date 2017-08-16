<?php

namespace TimeBundle\Security;

use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ApiFirewallMatcher
 *
 * @author saraelsayed
 */
class ApiFirewallMatcher 
{
    public static function matches(Request $request) {
        $url = $request->getPathInfo();
        return strpos($url, "/api") === 0 ;
    }

}
