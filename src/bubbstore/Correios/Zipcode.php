<?php

namespace bubbstore\Correios;

use FlyingLuscas\Correios\Client;
use FlyingLuscas\Correios\Service;
use bubbstore\Correios\Exceptions\ZipcodeException;

class Zipcode
{
    protected $zipcode;

    public function __construct($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    public function getZipcode()
    {
        return $this->zipcode;
    }

    public function find()
    {
        $correios = new Client;
        $result = $correios->zipcode()->find($this->getZipcode());

        if (!isset($result['zipcode'])) {
            throw new ZipcodeException('Invalid Zipcode');
        }

        $result['zipcode'] = preg_replace("/[^0-9]/", '', $result['zipcode']);

        return $result;
    }
}
