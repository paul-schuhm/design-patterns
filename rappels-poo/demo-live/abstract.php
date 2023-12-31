<?php

//Une 'interface' (en PHP) c'est une classe abstraite qui ne peut contenir que des méthodes abstraites
interface C
{
    //Dans une interface, tout est abstrait par définition
    function c();
}

interface D{
    function d();
}

interface E{
    function e();
}

//Une classe peut implémenter plusieurs interfaces
class ClasseQuiImplementeLesInterfacesCDetF implements C, D, E
{
    function c()
    {
        echo "Implémentation fournie à la méthode c définie dans l'interface C";
    }

    function d(){
        echo "Implémentation fournie à la méthode d définie dans l'interface D";
    }

    function e(){
        echo "Implémentation fournie à la méthode e définie dans l'interface E";
    }

}

//Equivalent à C (classe abstraite "pure", tout y est abstrait)
abstract class C2
{
    abstract function c();
}

//Une classe abstraite peut contenir des propriétés et des méthodes concrètes (avec implémentation), mais doit avoir au moins une méthode abstraite
abstract class A
{
    protected string $a = 'a';
    public function a()
    {
        echo 'a' . PHP_EOL;
    }
    //Une méthode abstraite permet de définir une signature d'une fonction
    //sans fournir d'implémentation
    public abstract function perimeterOfCircle(float $radius): float;
}

class B extends A
{
    //On fournit une implémentation à la méthode foo
    public function perimeterOfCircle(float $radius): float
    {
        return M_PI * $radius * 2;
    }
}

$b = new B();
$b->foo();
