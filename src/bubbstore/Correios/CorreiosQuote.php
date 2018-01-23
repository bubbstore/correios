<?php

namespace bubbstore\Correios;

use FlyingLuscas\Correios\Client;
use FlyingLuscas\Correios\Service;
use bubbstore\Correios\Exceptions\CorreiosQuoteException;

class CorreiosQuote
{

    protected $origin;

    protected $destination;

    protected $items = [];

    protected $services = [];

    protected $companyCode = null;

    protected $password = null;

    protected $diameter = 0;

    protected $maoPropria = 'N';

    protected $avisoRecebimento = 'N';

    protected $valorDeclarado = 0;

    protected $format = 'caixa';

    public function setCompanyCode($value)
    {
        $this->companyCode = $value;
        return $this;
    }

    public function getCompanyCode()
    {
        return $this->companyCode;
    }

    public function setFormat($value)
    {
        $this->format = $value;
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setPassword($value)
    {
        $this->password = $value;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setDiameter($value)
    {
        $this->diameter = $value;
        return $this;
    }

    public function getDiameter()
    {
        return $this->diameter;
    }

    public function setAvisoRecebimento($value)
    {
        $this->avisoRecebimento = $value;
        return $this;
    }

    public function getAvisoRecebimento()
    {
        return $this->avisoRecebimento;
    }

    public function setValorDeclarado($value)
    {
        $this->valorDeclarado = $value;
        return $this;
    }

    public function getValorDeclarado()
    {
        return $this->valorDeclarado;
    }

    public function setMaoPropria($value)
    {
        $this->maoPropria = $value;
        return $this;
    }

    public function getMaoPropria()
    {
        return $this->maoPropria;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin($value)
    {
        $this->origin = preg_replace("/[^0-9]/", '', $value);
        return $this;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function setDestination($value)
    {
        $this->destination = preg_replace("/[^0-9]/", '', $value);
        return $this;
    }

    public function setServices($value)
    {
        $this->services = $value;
        return $this;
    }

    public function getServices()
    {
        return $this->services;
    }

    public function setItems($value)
    {
        $this->items = $value;
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function calculate()
    {

        try {
            $correios = new Client;

            $correios = $correios->freight()
                    ->origin($this->getOrigin())
                    ->destination($this->getDestination())
                    ->services(implode(',', $this->getServices()))
                    ->declaredValue($this->getValorDeclarado())
                    ->useOwnHand($this->getMaoPropria());

            foreach ($this->getItems() as $item) {
                $correios->item($item[0], $item[1], $item[2], $item[3], $item[4]);
            }

            if ($this->getCompanyCode() && $this->getPassword()) {
                $correios->credentials($this->getCompanyCode(), $this->getPassword());
            }

            $result = $correios->calculate();

            return $result;
        } catch (\Exception $e) {
            throw new CorreiosQuoteException($e->getMessage(), $e->getCode());
        }
    }
}
