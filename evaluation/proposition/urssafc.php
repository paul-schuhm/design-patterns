#!/usr/bin/php
<?php

declare(strict_types=1);

use Urssaf\Repository\ContractorRepository;

require_once __DIR__ . '/vendor/autoload.php';

// Remarque : définir plutôt des Enum.
define('SIRET_LENGTH', 14);
define('VALID_ACTIVITIES', ['bnc', 'bic', 'bic-vente']);
define('VALID_TAX_SYSTEMS', ['ps', 'vfl']);

//A refactoriser (extraire et placer dans un module)
$pdo = new PDO("sqlite:" . __DIR__ . "/urssaf.db");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("CREATE TABLE IF NOT EXISTS contractor (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    full_name TEXT NOT NULL,
    siret TEXT NOT NULL UNIQUE,
    activity TEXT NOT NULL,
    tax_system TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_DATE
);");

// Extraction des arguments passés au script
$command = $argv[1] ?? null;

switch ($command) {
    case 'add':
        $fullName = $argv[2] ?? null;
        $siret = $argv[3] ?? null;
        $activity = $argv[4] ?? null;
        $taxSystem = $argv[5] ?? null;

        if (!$fullName || !$siret || !$activity || !$taxSystem) {
            echo "Erreur: Arguments manquants pour 'add'.\n";
            exit(1);
        }
        // Valider les input (SIRET, activity, taxSystem)...

        $isValidActivity = in_array($activity, VALID_ACTIVITIES);
        $isValidTaxSystem = in_array($taxSystem, VALID_TAX_SYSTEMS);
        $isValidSiret = strlen($siret) === SIRET_LENGTH;
        $isValidInput = $isValidSiret && $isValidTaxSystem && $isValidActivity;

        if (!$isValidInput) {
            echo "Erreur: Arguments invalides pour'add'.\n";
            exit(1);
        }

        try {
            new ContractorRepository($pdo)->save($fullName, $siret, $activity, $taxSystem);
        } catch (PDOException | Exception $e) {
            echo  $e->getMessage() . PHP_EOL;
            exit(1);
        }
        break;

    case 'ls':
        $contractors = new ContractorRepository($pdo)->findall();
        foreach ($contractors as $contractor) {
            echo $contractor . PHP_EOL;
        }
        echo sprintf("Total: %2d", count($contractors)) . PHP_EOL;
        break;

    case 'dry-declare':
        $id = isset($argv[2]) ? (int)$argv[2] : null;
        $caHt = isset($argv[3]) ? (float)$argv[3] : null;

        if (!$id || !$caHt) {
            echo "Erreur: Arguments manquants pour 'dry-declare'.\n";
            exit(1);
        }

        //Récupérer le contractor, instancier la Strategy pour calculer cotisations sociales/l'impôt et afficher le rapport
        $contractor = new ContractorRepository($pdo)->find($id);
        echo $contractor->buildReport($caHt);
        break;

    //Par convention, lancer une commande sans argument affiche le manuel
    default:
        echo "Usage:\n";
        echo "  php urssafc.php add \"NOM_COMPLET\" SIRET REGIME_ACTIVITE REGIME_FISCAL\n";
        echo "  REGIME_ACTIVITE : bic-vente, bic, bnc\n";
        echo "  REGIME_FISCAL : vfl (versement fiscal libératoire), ps (prélèvement à la source)\n";
        echo "  php urssafc.php ls\n";
        echo "  php urssafc.php dry-declare ID CA_HT\n";
        exit(1);
}
