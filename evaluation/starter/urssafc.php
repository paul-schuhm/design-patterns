<?php

/**
 * TEMPLATE DE DEPART
 * Application CLI cliente d'un système de gestion des autoentrepreneurs
 */

declare(strict_types=1);

//Chargement de l'auto-loader
require_once __DIR__ . '/vendor/autoload.php';

// 1. Configuration PDO SQLite...
// 2. Création automatique de la table si elle n'existe pas...

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
        // 1. Valider le SIRET, gérer les doublons, et insérer en BDD
        break;

    case 'ls':
        // 1. Lister les microentreprises
        break;

    case 'dry-declare':
        $id = isset($argv[2]) ? (int)$argv[2] : null;
        $caHt = isset($argv[3]) ? (float)$argv[3] : null;

        if (!$id || !$caHt) {
            echo "Erreur: Arguments manquants pour 'dry-declare'.\n";
            exit(1);
        }
        //1. Récupérer les données de l'auto-entreprise
        //2. Injecter la Strategy a un objet Contractor (autoentreprise) 
        //3. Calculer les cotisations sociales, appliquer la fiscalité et construire le rapport

        //Imprimer le rapport
        echo $contractor->buildReport($caHt);
        break;

    //Par convention, lancer une commande sans argument affiche le manuel
    default:
        echo "Usage:\n";
        echo "  php urssafc.php add \"NOM_COMPLET\" SIRET REGIME_ACTIVITE REGIME_FISCAL\n";
        echo "  php urssafc.php ls\n";
        echo "  php urssafc.php dry-declare ID CA_HT\n";
        exit(1);
}
