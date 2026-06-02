<?php

namespace Urssaf\Model;

use Urssaf\Model\AbstractActivityStrategy;

class BNCActivityStrategy extends AbstractActivityStrategy
{

    public function name(): string
    {
        return 'BNC';
    }

    protected function cotisationRate(): float
    {
        return 22 / 100;
    }
    protected function taxDischargePayment(): float
    {
        return 2.2 / 100;
    }
    protected function abatementRate(): float
    {
        return 34 / 100;
    }

    public function calculateSpecificSubsidy(float $caHt): float
    {
        // Aide BNC : déduction forfaitaire de 15% si CA HT < 1500 EUROS
        return ($caHt < 1500 && $caHt > 0) ? ($caHt * 0.15) : 0.0;
    }
}
