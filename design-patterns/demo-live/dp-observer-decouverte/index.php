<?php
/**
 * Démo en live, en partant de la documentation sur le DP Observer sur le site de Refactoring Guru https://refactoring.guru/design-patterns/observer
 */

class Publisher
{
    /**
     * @var Suscriber[] La liste des suscribers
     */
    private ?array $suscribers = null;

    //Notre état
    private int $counter = 0;

    public function suscribe(Suscriber $s){
        //On ajoute le suscriber à la liste
        $this->suscribers[] = $s;
    }

    public function unsuscribe(Suscriber $s){
        //Désinscire un suscriber de la liste
        //Todo
    }

    public function notifySuscribers(){
        foreach($this->suscribers as $suscriber){
            $suscriber->update();
        }
    }

    public function incrementCounter(){
        //L'état change
        $this->counter += 1;
        //Je notifie tous les objets qui écoutent (suscribers)
        $this->notifySuscribers();
    }
}

interface Suscriber
{
    public function update();
}

class RandomDuck implements Suscriber{

    public function update(){
        echo "Coin coin + 1 !" . PHP_EOL;
    }
}

//Premier test du pattern :

$publisher = new Publisher();
$ducks = [new RandomDuck(), new RandomDuck(), new RandomDuck()];

//Enregistrer (s'inscrire)
foreach($ducks as $duck){
    $publisher->suscribe($duck);
}

//Le changement d'état du compteur va notifier tous les canards qui écoutent et déclencher une vague d'update
$publisher->incrementCounter();

