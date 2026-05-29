<?php

/*
Ici on choisit une classe abstraite pour centraliser l'implémentation de la génération du rapport, commune à tous les régimes. On fait un mélange entre le pattern *Strategy* et *Template Method*
*/

abstract class AbstractActivityStrategy
{
    //Retourne le rapport (qui sera affiché sur la sortie standard)
    public function buildReport(float $caHt, string $taxSystem): string
    {
        //À implémenter. Dépendra notamment du régime fiscal.
    }
    //Retourne le taux de cotisation social
    abstract protected function cotisationRate(): float;
    //Retourne le taux de prélèvement dans le cadre du régime fiscal "Versement fiscal libératoire" (vfl)
    abstract protected function taxDischargePayment(): float;
    //Retourne le taux d'abattement fiscal dans le cadre du régime fiscal "Prélèvement à la source" (ps)
    abstract protected function abatementRate(): float;

    //De la logique métier propre à chaque régime d'activité
    //Calcul des indemnités de frais d'exploitation
    abstract protected function specificSubsidy(float $caHt): float;
}
