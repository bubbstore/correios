<?php

namespace bubbstore\Correios\Exceptions;

use Exception;

class CorreiosTrackingException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }
}
