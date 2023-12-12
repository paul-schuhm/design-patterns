<?php

class City{
    
    //État (attributs)
    private string $name;
    private string $county;

    // Type hinting (indication de type)
    public function __construct(string $name, string $county)
    {
        $this->name = $name;
        $this->county = $county;
    }

    /**
     * Retourne une représentation de l'objet sous forme de chaîne de caractères
     */
    public function __toString(): string
    {
        return sprintf("La ville %s est dans le département %s", $this->name, $this->county);
    }

    //PHP 7 ou 8
    // public function __construct(
    //     public string $name,
    //     public string $county
    // )
    // {}
}