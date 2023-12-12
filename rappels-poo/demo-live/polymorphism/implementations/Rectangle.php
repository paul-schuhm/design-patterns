<?php

require_once __DIR__ .'/../IArea.php';


//Rectangle est de type Area
class Rectangle implements Area
{
    public function __construct(
        private float $width,
        private float $height
    ) {
    }

    public function area(): float
    {
        return $this->width * $this->height;
    }
}