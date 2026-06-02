<?php

namespace Urssaf\Model;

use Urssaf\Model\AbstractActivityStrategy;

class BICActivityStrategy extends AbstractActivityStrategy
{
    public function name(): string
    {
        return 'BIC';
    }

    protected function cotisationRate(): float
    {
        return 12.8 / 100;
    }
    protected function taxDischargePayment(): float
    {
        return 1.7 / 100;
    }
    protected function abatementRate(): float
    {
        return 50 / 100;
    }
    public function calculateSpecificSubsidy(float $caHt): float
    {
        return 0.0;
    }
}
