<?php

require_once './IGeometry.php';


//Circle est de type IGeometry
class Circle implements IGeometry
{
    public function __construct(
        private float $radius
    ) {
    }
    public function area(): float
    {
        return M_PI * $this->radius * $this->radius;
    }

    public function perimeter(): float
    {
        return 2 * M_PI * $this->radius;
    }
}