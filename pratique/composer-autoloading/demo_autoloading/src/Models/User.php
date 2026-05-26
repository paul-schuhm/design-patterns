<?php

//Attention, un chemin relatif est interprété **à partir de l'emplacement du fichier qui exécute le script principal**,
//index.php ici !
//require_once './Post.php'; X ne marchera pas


//Solution : définir un chemin absolu (path vers la racine du projet) et l'utiliser dans tous les imports.
require_once 'config.php';
require_once ABS_PATH . 'src/Models/Post.php';

class User
{

    private static int $last_id = 1;

    public readonly int $id;

    //Remarque : on pourrait aussi utiliser un constructeur privé et une méthode 'factory' static create::().
    public function __construct(
        public string $firstName,
        public string $lastName,
        public array $posts = [],
        ?int $id = null,
    ) {
        $this->id = $id ?? static::$last_id;
        static::$last_id++;
    }
}
