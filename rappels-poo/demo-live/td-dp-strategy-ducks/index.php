<?php

/**
 * TD : Développer une application qui est un jeu de simulation de mare aux canards.
 * On a commencé avec l'héritage simple, constaté les problèmes, puis
 * on a progressivement redesigné le système pour implémenter le pattern Strategy.
 * On arrive avec un code très modulaire où l'on a préféré la composition d'objets
 * à l'héritage
 * Source : Ce TD est complètement inspiré d'un exemple proposé dans le livre "Design Patterns Tête la première"
 * publié en france Chez Eyrolles, 2009
 * (cf Chapitre 1, page 2 )
 */

abstract class Duck
{
    private Gossiping $gossipBehavior;
    private Flying $flyingBehavior;

    //A la construction de l'objet, on va lui fournir des instances de Gossiping et Flying
    //Composition d'objets
    //Injection de dépendances (via la constructeur)
    public function __construct(
        Flying $flyingBehavior,
        Gossiping $gossipBehavior
    ) {
        $this->flyingBehavior = $flyingBehavior;
        $this->gossipBehavior = $gossipBehavior;
    }

    //Comportements qui ne changent pas
    public abstract function print();

    public function swim()
    {
        echo "[Swimming]" . PHP_EOL;
    }

    public function fly(){
        $this->flyingBehavior->fly();
    }

    public function gossip(){
        $this->gossipBehavior->gossip();
    }
}

//Canards Colvert, Challans

class Colvert extends Duck
{
    public function __construct(
        Flying $flyingBehavior,
        Gossiping $gossipBehavior
    ) {
        parent::__construct($flyingBehavior, $gossipBehavior);
    }

    public function print()
    {
        echo "[Sprite Colvert]" . PHP_EOL;
    }
}

//Canard en plastique
class RubberDuck extends Duck
{
    public function __construct(
        Flying $flyingBehavior,
        Gossiping $gossipBehavior
    ) {
        parent::__construct($flyingBehavior, $gossipBehavior);
    }

    public function print()
    {
        echo "[Sprite RubberDuck]" . PHP_EOL;
    }
}


//Définir tous les comportements liés à la production de sons
interface Gossiping
{
    public function gossip();
}

//Définir tous les comportements liés au vol
interface Flying
{
    public function fly();
}

//Comportements sonores 
class CoinCoin implements Gossiping
{
    public function gossip()
    {
        echo "Coin coin !" . PHP_EOL;
    }
}

class CouicCouic implements Gossiping
{
    public function gossip()
    {
        echo "Couic couic !" . PHP_EOL;
    }
}

class Mute implements Gossiping
{
    //Ne fais aucun bruit
    public function gossip()
    {
    }
}

//Comportements de vol

class FlyingWithWings implements Flying
{
    public function fly()
    {
        echo "[Flying with wings]" . PHP_EOL;
    }
}

class NotFlying implements Flying
{
    public function fly()
    {
    }
}

//A faire
// class BlueSwedenDuck extends Duck
// {
//     public function print()
//     {
//         echo "[Sprite BlueSwedenDuck]" . PHP_EOL;
//     }
// }
// //Canard en bois
// class WodenDuck extends Duck
// {
//     public function print()
//     {
//         echo "[Sprite WodenDuck]" . PHP_EOL;
//     }
// }

$ducks = [
    //Ici on injecte les comportements (les "Stratégies" du DP Strategy) via le constructeur
    //On peut donc les changer dynamiquement
    new Colvert(new FlyingWithWings(), new CoinCoin()),
    new RubberDuck(new NotFlying(), new CouicCouic())
];

//Game loop :
foreach ($ducks as $duck) {
    $duck->fly();
    $duck->gossip();
    $duck->print();
}
