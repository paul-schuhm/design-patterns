<?php


namespace Paul\DP;

/**
 * Imaginons que ce soit une classe de mon domaine métier qui a besoin d'être mis à jour
 * lorsqu'un évènement a lieu. Comment l'intégrer à mon DP ? Implémentation ? Composition ?
 */
class BusinessObject
{

    public function __construct(private $state)
    {
    }
}
