<?php

/**
 * Suggestion de correction de l'exercice 1
 */


//Code client (reste du monde)

//Dépendance avec la classe City (copier/coller)
// include, require, import, use, etc. Indique une dépendance
require_once './City.class.php';
require_once './CityWithArea.class.php';

//Un objet doit être instancié dans un état valide
$rennes = new CityWithArea('Rennes', 'Ille-Et-Vilaine', 'Bretagne');
$nantes = new CityWithArea('Nantes', 'Loire-Atlantique', 'Pays de La Loire');

//echo équivalent de console.log() en JS
echo $rennes . PHP_EOL;
echo $nantes . PHP_EOL;
