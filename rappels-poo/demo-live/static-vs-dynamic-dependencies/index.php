<?php

/**
 * Cette démo illustre la différence entre une dépendance statique ("at compile time") et dynamique ("at run time").
 * Usage : php index.php shape [length].
 * Exemples : 
 * php index.php circle 2
 * php index.php square
 * php index.php square 5
 * En fonction de l'input du script, on invoque une implémentation de IGeometry ("at run time")
 * Le code métier (calcul de l'aire et de la surface) est indépendant de l'implémentation choisie (Square ou Circle),
 * il ne dépend **que** de l'interface IGeometry. IGeometry est une dépendance statique (ou "vraie" dépendance), 
 * le code métier (calcul de l'aire et périmètre ici) doit changer **seulement si** IGeometry change
 */

//**Dépendance statique** ("at compile time"). Le code client est dépendant à la compilation de IGeometry
require_once './IGeometry.php';

$shapeToRequire = $argv[1] ?? 'undefined';

//Gestion d'erreur des paramètres fournis au script
if($shapeToRequire === 'undefined'){
    echo "Usage : php index.php shape [length]". PHP_EOL;
    echo "shape : circle ou square" . PHP_EOL;
    echo "length (optionnel) : une valeur numérique. Par défaut vaut 1." . PHP_EOL;
    exit;
}

//Si une dimension est fournie en argument, on la récupère, sinon elle vaut 1 par défaut.
$length = $argv[2] ?? 1;

// **Dépendances dynamiques** (at run time). Ces dépendances sont d'une autre nature. Le code client
// doit utiliser une implémentation de IGeometry au moment de l’exécution (il faut bien executer du code à un moment donné !)
// Cette dépendance est donc de nature différente que celle avec IGeometry. Le code métier (ci dessous) ne dépend pas d'une 
// implémentation particulière et ne devra pas être modifié si on ajoute ou supprime une forme. Je peux choisir quelle implémentation
// invoquer en passant en paramètre la forme que je souhaite à mon programme. 
// (Voir open/closed principle des principes SOLID) 
switch($shapeToRequire){
    case 'circle':
        require_once './Circle.php';
        $shape = new Circle(radius: $length);
        break;
    case 'square':
        require_once './Square.php';
        $shape = new Square(size: $length);
        break;
    default:
        break;
}

//Code métier : calcul de l'aire et du périmètre des formes géométriques
//Dépendance statique (at compile time). Le code client est dépendant à la compilation de IGeometry. On le voit ici avec
//l'appel aux méthodes perimeter() et area() définies (spécifiées) par IGeometry.
$perimeter = $shape->perimeter();
$area = $shape->area();

echo "Forme choisie : " . $shapeToRequire . PHP_EOL;
echo "Périmètre : " . $perimeter. PHP_EOL;
echo "Surface : " . $area. PHP_EOL;