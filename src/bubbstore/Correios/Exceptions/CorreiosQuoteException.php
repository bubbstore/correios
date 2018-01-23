<?php

namespace bubbstore\Correios\Exceptions;

use Exception;

class CorreiosQuoteException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
