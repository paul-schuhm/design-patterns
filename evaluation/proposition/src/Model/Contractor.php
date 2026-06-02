<?php

namespace Urssaf\Model;

use Urssaf\Model\AbstractActivityStrategy;

/**
 * Une instance de microentreprise
 */
class Contractor
{
    public function __construct(
        protected ?int $id,
        protected string $fullName,
        //Définir un enum backed pour avoir la représentation en string (Label à afficher)
        protected string $taxSystem,
        protected string $siret,
        protected AbstractActivityStrategy $strategy
    ) {}

    public function buildReport(float $caHt): string
    {

        define('PRECISION', 1e-10);

        $result = '';

        //A refactorer. Formatage et padding à ajouter.
        $reportData = $this->strategy->buildReport($caHt, $this->taxSystem);

        $result .= $this->fullName;
        $result = sprintf("%s|%s|%s", $this->fullName, $this->strategy->name(), $this->taxSystem);
        $result = sprintf("%s %s\n", "CA HT mensuel: ", $caHt);

        if (isset($reportData['subsidy']) && $reportData['subsidy'] > PRECISION)
            $result .= "Aide spécifique: " . $reportData['subsidy'] . " EUROS\n";

        $result .= "Cotisations sociales: " . $reportData['social_tax'] . " EUROS\n";
        if (isset($reportData['revenu_imposable']))
            $result .= "Revenu imposable: " . $reportData['revenu_imposable'] . " EUROS\n";
        if (isset($reportData['tax']))
            $result .= "Montant de l'impôt à prélever: " . $reportData['tax'] . " EUROS\n";
        $result .= "CA TTC mensuel: " . $reportData['ca_ttc'] . " EUROS\n";

        return $result;
    }

    public function __toString()
    {
        return sprintf("%d %s", $this->id, $this->fullName);
    }
}
