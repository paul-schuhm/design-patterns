<?php

require_once __DIR__ .'/../IArea.php';


//Circle est de type Area
class Circle implements Area
{
    public function __construct(
        private float $radius
    ) {
    }
    public function area(): float
    {
        return M_PI * $this->radius * $this->radius;
    }
}