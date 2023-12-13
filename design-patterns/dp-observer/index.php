<?php

require './vendor/autoload.php';


use Paul\DP\Publisher;
use Paul\DP\Suscriber;

$publisher = new Publisher();


//Implémentation directe
$publisher->addSuscribers(
    new Suscriber('foo'),
    new Suscriber('bar'),
    new Suscriber('baz')
);

//Cas réel

$publisher->doSomething();
