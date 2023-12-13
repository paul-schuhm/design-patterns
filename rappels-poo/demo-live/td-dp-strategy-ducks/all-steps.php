<?php

/**
 * TD : Développer une application qui est un jeu de simulation de mare aux canards.
 * Même que index.php mais avec toutes les étapes du projet, de l'héritage jusqu'au pattern Strategy
 * (chaque etage du projet est mis dans un namespace)
 */


namespace heritageSolution {

    //1.on commence par utiliser l'héritage. La classe print est abstraite car chaque canard a un aspect différent, les autres méthodes sont concrètes car tous les canards gossip et swim.

    abstract class Duck
    {

        public function __construct()
        {
        }

        public function gossip()
        {
            echo 'Coin coin !' . PHP_EOL;
        }

        public function swim()
        {
            echo 'Le canard nage' . PHP_EOL;
        }

        public function fly()
        {

            echo 'Le canard vole' . PHP_EOL;
        }

        abstract public function print();
    }


    class BlueSuedeDuck extends Duck
    {

        public function print()
        {
            echo 'Afficher sprite Canard Bleu de Suède' . PHP_EOL;
        }

        public function fly()
        {

            echo 'Le canard vole' . PHP_EOL;
        }
    }

    class ChallansDuck extends Duck
    {

        public function print()
        {
            echo 'Afficher sprite Canard Challans' . PHP_EOL;
        }
    }

    class ColvertDuck extends Duck
    {
        public function print()
        {
            echo 'Afficher sprite Canard Colvert' . PHP_EOL;
        }
    }

    //3. Je redéfinis fly pour ne rien faire, et gossip pour couiner

    class PlasticDuck extends Duck
    {

        public function __construct()
        {
        }

        public function gossip()
        {
            echo 'Couic couic!' . PHP_EOL;
        }

        public function swim()
        {
            echo 'Le canard nage' . PHP_EOL;
        }

        public function fly()
        {
        }

        public function print()
        {
            echo 'Afficher sprite Canard Colvert' . PHP_EOL;
        }
    }
}


namespace interfaceSolution {


    abstract class Duck
    {

        public function __construct()
        {
        }

        public function swim()
        {
            echo 'Le canard nage' . PHP_EOL;
        }

        abstract public function print();
    }

    interface Gossip
    {
        public function gossip();
    }

    interface Fly
    {
        public function fly();
    }


    class MandarinDuck extends Duck implements Gossip, Fly
    {
        public function __construct()
        {
        }

        public function gossip()
        {
            echo 'Coin coin!' . PHP_EOL;
        }

        public function fly()
        {
            echo 'Vol !' . PHP_EOL;
        }

        public function print()
        {
            echo 'Afficher sprite Canard Mandarin' . PHP_EOL;
        }
    }

    //Maintenant si j'ajoute une classe de canard je dois réimplémenter fly et gossip !! Possiblement plusieurs fois la meme chose. Pas DRY, impossible à maintenir, introduction de bugs (oublis). Et si fly doit etre modifié, je dois le modifier partout !
}

namespace strategySolution {

    //Encore une fois, on passe de l'héritage à la composition

    //On isole les comportements qui changent dans des interfaces
    interface FlyBehavior
    {
        public function fly();
    }

    interface GossipBehavior
    {
        public function gossip();
    }

    //On les fait implémenter dans des classes
    class FlyWithWings implements FlyBehavior
    {
        public function fly()
        {
            echo 'Vol !' . PHP_EOL;
        }
    }

    class NotFlying implements FlyBehavior
    {
        public function fly()
        {
            echo 'Ne vole pas' . PHP_EOL;
        }
    }

    //Faire la même pour le comportement gossip
    abstract class Duck
    {

        //Ce qui change: composition (ici injecté dans le constructeur => injection de dépendances). On compose notre classe avec des comportements implémentés (stratégie)
        public function __construct(
            public FlyBehavior $flyBehavior
        ) {
        }

        //Ce qui ne change pas
        public function swim()
        {
            echo 'Le canard nage' . PHP_EOL;
        }

        public function fly()
        {
            //On appelle ici le comportement (i.e notre "strategy")
            $this->flyBehavior->fly();
        }

        //Cette fonction a toujours du sens car chaque canard a son propre sprite
        abstract function print();
    }

    class PlasticDuck extends Duck
    {

        public function __construct(FlyBehavior $flyBehavior)
        {
            parent::__construct($flyBehavior);
        }

        public function print()
        {
            echo 'Sprite canard en plastique' . PHP_EOL;
        }

        public function setFlyBehavior(FlyBehavior $flyBehavior)
        {
            $this->flyBehavior = $flyBehavior;
        }
    }

    //Code test
    $duck = new PlasticDuck(new FlyWithWings());
    $duck->fly();
    //Je peux changer dynamiquement (au cours de l'execution, at run time) le comportement de vol à présent !
    $duck->setFlyBehavior(new NotFlying());
    $duck->fly();
}