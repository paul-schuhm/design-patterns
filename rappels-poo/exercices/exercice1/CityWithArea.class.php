<?php

require_once './City.class.php';

class CityWithArea extends City{

    private string $region;

    public function __construct(string $name, string $county, string $region)
    {
        //"::" opérateur de portée
        parent::__construct($name, $county);
        $this->region = $region;
    }

    public function __toString(): string
    {
        return sprintf("%s de la région %s", parent::__toString(), $this->region);
    }
}