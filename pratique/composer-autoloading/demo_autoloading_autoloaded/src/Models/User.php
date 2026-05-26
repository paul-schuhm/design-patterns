<?php

namespace Ps\App\Models;

class User
{
    private static int $last_id = 1;

    public readonly int $id;

    //Remarque : on pourrait aussi utiliser un constructeur privé et une méthode 'factory' static create::().
    public function __construct(
        public string $firstName,
        public string $lastName,
        /** @var array<int, Post> $posts */
        public array $posts = [],
        ?int $id = null,
    ) {
        $this->id = $id ?? self::$last_id;
        self::$last_id++;
    }
}
