<?php

namespace Urssaf\Model;

use Urssaf\Model\AbstractActivityStrategy;

class BICVenteActivityStrategy extends AbstractActivityStrategy
{

    public function name(): string
    {
        return 'BICVente';
    }


    protected function cotisationRate(): float
    {
        return 22 / 100;
    }
    protected function taxDischargePayment(): float
    {
        return 1 / 100;
    }
    protected function abatementRate(): float
    {
        return 71 / 100;
    }
    public function calculateSpecificSubsidy(float $caHt): float
    {
        return ($caHt > 3000) ? 200.0 : 0.0;
    }
}
