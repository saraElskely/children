<?php

namespace TimeBundle\Security\Guard;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Description of JWTAuthenticator
 *
 * @author saraelsayed
 */
class JWTAuthenticator extends BaseAuthenticator {
    //put your code here
    protected function getTokenExtractor()
    {
        $chainExtractor = parent::getTokenExtractor();
        $chainExtractor->addExtractor(new AuthorizationHeaderTokenExtractor('JWT', 'Authorization'));
        return $chainExtractor;
    }
    
    

}
