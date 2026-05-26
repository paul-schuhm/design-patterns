<?php

namespace Ps\App\Models;

class Post
{
    //Une variable de classe (static)
    private static int $last_id = 1;

    public readonly int $id;

    public function __construct(
        readonly public string $slug,
        readonly public string $title,
        //Je ne respecte pas ici la même convention de casse (on fixera cela après avec un linter)
        readonly public \DateTimeImmutable $date_publication,
        readonly public int $author,
        ?int $id = null,
    ) {
        //Opérateur null coealescing : équivalent à $this->id = isset($id) ? $id : static::$last_id;
        $this->id = $id ?? self::$last_id;
        self::$last_id++;
    }
}
