<?php

/**
 * Démo générale sur les concepts POO : classe, objet, attribut, méthode, héritage simple, méthode et attribut statiques
 */

//Patron de conception (définition des objets)
class Geometry2D
{

    //Variable/Méthode static c'est quelque-chose propre à la classe et non aux objets

    //Compteur d'instances de Geometry2D
    private static int $nbOfInstances = 0;

    //"Getter" (méthode qui retourne la valeur d'une propriété)
    public static function nbOfInstances(): int
    {
        return static::$nbOfInstances;
    }


    //Constructeur : méthode appelée pour instancier l'objet (new)
    public function __construct(int $nbOfComputations = 0)
    {
        //Initialiser l'état de votre objet à la création
        // $this->nbOfComputations = $nbOfComputations;
        // echo "Une objet Geometry2D a été instancié" . PHP_EOL;
        // echo "nbOfComputations=" . $this->nbOfComputations . PHP_EOL;
        //Incrémenter de 1 quand j'instance un objet
        static::$nbOfInstances++;
    }

    /**
     * État
     */
    //Variables de classe (ou attribut, propriété)
    protected float $pi = 3.1415926535898;

    /**
     * Compteur de calculs réalisés pour le monde extérieur
     */
    private int $nbOfComputations = 0;

    /**
     * Méthode
     * Retourne le périmètre d'un cercle de rayon $radius
     * @param float $radius Le rayon du cercle
     * @return float
     */
    public function computePerimeterOfCircle(float $radius): float
    {
        $this->nbOfComputations = $this->nbOfComputations + 1;
        return 2 * $this->pi * $radius;
    }

    /**
     * Retourne la surface d'un cercle de rayon $radius
     * @param float $radius Le rayon du cercle
     * @return float
     */
    public function computeSurfaceOfCircle(float $radius) : float{
        return $this->pi * $radius * $radius ;
    }
}

class Geometry3D extends Geometry2D{
    /**
     * Retourne le volume d'un cylindre de rayon $radius et de hauteur $height
     * @param float $radius Le rayon du cylindre
     * @param float $height La hauteur du cylindre
     * @return float
     */
    public function computeVolumeOfCylinder(float $radius, float $height) : float{
        //Réutilisation de code
        return $this->computeSurfaceOfCircle($radius) * $height;
    }

}


//Instance de la classe Geometry2D
//La variable $geometry2D contient "une référence" (un pointeur) vers l'objet de type Geometry2D
// echo Geometry2D::nbOfInstances() . PHP_EOL;

$a = new Geometry2D();
$b = new Geometry2D();
$c = new Geometry3D();

echo $c->computePerimeterOfCircle(1) . PHP_EOL;
echo $c->computeVolumeOfCylinder(1, 10) . PHP_EOL;

//Propriété de la classe
// echo Geometry2D::nbOfInstances() . PHP_EOL;

// //Modifier l'état interne
// $geometry2D->pi = 2;

// //Acceder à l'état interne
// echo $geometry2D->pi;

//Appel la méthode computePerimeterOfCircle de l'objet $geometry2D
// $perimeter = $geometry2D->computePerimeterOfCircle(1);
// echo $perimeter . PHP_EOL;
