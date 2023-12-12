<?php

/**
 * Dépendance "statique" (suit le flot de contrôle), toujours nécessaire
 */
require_once __DIR__ . '/IArea.php';

/**
 * Dépendance "dynamique" (dépendance 'faible', uniquement nécessaire à l’exécution)
 */
require_once __DIR__ . '/implementations/Rectangle.php';
require_once __DIR__ . '/implementations/Circle.php';
require_once __DIR__ . '/implementations/Square.php';

$shapes = [new Rectangle(1, 2), new Rectangle(3, 4), new Square(1), new Square(5), new Circle(1)];

//Le code client n'a pas besoin d'être modifié lorsque je rajoute des formes
//Grâce à l'interface IArea, on a fait de l'inversion de dépendances. Je peux modifier et remplacer
//des formes, tant qu'elles implémentent IArea, le code client n'aura aucune raison de changer.
//Grâce au polymorphisme, le code client (index.php ici) ne dépend pas des détails d'implémentation (implémentations de IArea)

foreach ($shapes as $shape) {
    //Le polymorphisme en action : une même méthode execute du code différent
    echo $shape->area() . PHP_EOL;
}
