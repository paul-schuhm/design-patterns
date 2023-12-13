<?php

/**
 * L'interface IGeometry définit les spécifications (signatures) des fonctions (méthodes) area et perimeter.
 * Toute classe manipulée par le code client doit implémenter cette interface.
 * Le code client est dépendant de IGeometry, mais ne sera pas dépendant (at compile time) des implémentations
 * de cette interface. C'est l'intérêt du polymorphisme, il permet d'inverser les dépendances et de rendre le code
 * modulable (on peut remplacer une implémentation d'IGeometry par une autre sans modifier le code client)
 */
interface IGeometry
{
    /**
     * Retourne la surface (unité arbitraire) de la forme géométrique
     */
    public function area(): float;
    /**
     * Retourne le périmètre (unité arbitraire) de la forme géométrique
     */
    public function perimeter(): float;
}
