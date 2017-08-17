<?php

namespace TimeBundle\Exception;

use TimeBundle\constant\Exceptions;

/**
 * Description of TimeBundleException
 *
 * @author saraelsayed
 */
class TimeBundleException extends \Exception
{
    public function __construct(int $code = 0) {
        parent::__construct(Exceptions::EXCEPTION_MESSAGES[$code], $code);
    }
    
    public function getStatusCode()
    {
        return Exceptions::EXCEPTION_STATUS_CODE[$this->code];
    }
}
