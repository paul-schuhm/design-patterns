<?php

require_once './IGeometry.php';

//Circle est de type IGeometry, car elle implémente son interface.
class Square implements IGeometry
{
    public function __construct(
        private float $size
    ) {
    }
    public function area(): float
    {
        return $this->size * $this->size;
    }

    public function perimeter(): float
    {
        return 4 * $this->size;
    }
}