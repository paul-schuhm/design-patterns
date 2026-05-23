<?php

require_once __DIR__ .'/../IArea.php';


//Square est de type Area
class Square implements Area
{
    public function __construct(
        private float $size,
    ) {
    }

    public function area(): float
    {
        return $this->size * $this->size;
    }
}