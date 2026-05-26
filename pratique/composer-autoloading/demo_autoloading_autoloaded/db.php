<?php

require './vendor/autoload.php';

use Ps\App\Models\Post;
use Ps\App\Models\User;


/**
 * Liste des users en base
 */
$users = [
    new User("John", "Doe"),
    new User("Jane", "Doe"),
];

/**
 * Liste des posts en base
 */
$posts = [
    new Post("post-a", "Article A", new DateTimeImmutable("2025-11-01"), 1),
    new Post("post-b", "Article B", new DateTimeImmutable("2025-11-02"), 2),
    new Post("post-c", "Article C", new DateTimeImmutable("2025-11-03"), 1),
    new Post("post-d", "Article D", new DateTimeImmutable("2025-11-04"), 2),
    new Post("post-e", "Article E", new DateTimeImmutable("2025-11-05"), 1),
    new Post("post-f", "Article F", new DateTimeImmutable("2025-11-06"), 2)
];
