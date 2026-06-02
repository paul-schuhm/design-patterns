<?php

namespace Urssaf\Model;

use InvalidArgumentException;

/**
 * Stratégie (Régime d'activité)
 */
abstract class AbstractActivityStrategy
{

    //Logique commune à tous les régimes d'activité, construction de données pour imprimer le rapport.
    public function buildReport(float $caHt, string $taxSystem): array
    {

        $social_tax = $caHt * $this->cotisationRate();
        $subsidy = $this->calculateSpecificSubsidy($caHt);

        //A refactoriser en ValueObject Immutable (pour documenter les clefs)
        $reportData = [];
        $reportData['social_tax'] = $social_tax;
        $reportData['subsidy'] = $subsidy;

        //Violation de l'open/close principle "acceptable" pour le moment.
        //Modification des régimes fiscaux = modifier ce code (Refactor possible : utiliser une stratégie supplémentaire, externaliser)
        switch ($taxSystem) {
            case 'vfl':
                $tax = $caHt * $this->taxDischargePayment();
                $reportData['tax'] = $tax;
                $reportData['ca_ttc'] = $caHt - $social_tax - $tax + $subsidy;
                break;
            case 'ps':
                $reportData['revenu_imposable'] = $caHt * (1 - $this->abatementRate());
                $reportData['ca_ttc'] = $caHt - $social_tax + $subsidy;
            default:
                throw new InvalidArgumentException("Régime fiscal inconnu");
        }
        return $reportData;
    }

    abstract public function name(): string;

    //Ces méthodes ne fournissent que des valeurs, on pourrait les remplacer par des variables statiques
    //mais un user de la classe pourrait oublier de les override, aucune primitive pour le forcer alors
    //que des méthodes abstraites DOIVENT être implémentées, pas le choix. Permet de s'assurer du bon usage
    //de cette classe
    abstract protected function cotisationRate(): float;
    abstract protected function taxDischargePayment(): float;
    abstract protected function abatementRate(): float;

    //D'autre logique métier propre à chaque régime
    //Par exemple, aide forfaitaire, indemnité d'aide de l'État
    abstract public function calculateSpecificSubsidy(float $caHt): float;
}
