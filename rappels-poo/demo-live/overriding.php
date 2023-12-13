<?php

/**
 * Démo sur la surcharge (overriding) de méthodes dans une classe enfant. On dit qu'une classe enfant surcharge (override) une méthode lorsqu'elle redéfinit l'implémentation de la méthode héritée de son parent. 
 */

class A{
    public function foo(){
        echo "Foo" . PHP_EOL;
    }
}

class B extends A{

    //Surcharge (overriding) de la méthode foo de la classe A
    public function foo(){
        echo "Bar" . PHP_EOL;
    }
}

$a = new A();
$b = new B();

$a->foo();
$b->foo();