<?php

/**
Partie 1/4  : Présente le concept de classe, d'objet, l'opérateur new,
le constructeur d'un objet, l'héritage simple, la visibilité,
l'overriding
*/

//Déclarer une classe = template des objets
class User
{
//------------------------------------- Etats internes (machinerie)

  //Etats internes
  //Attribut/Variabe de classe
  //Visibilité : public, private, protected
  //Par défaut, public
  //On préfére privé = encapsuler les états internes

  //Constante de classe
    const string FORMAT = 'LOWERCASE';
  //Privé pour l'exterieur, public pour les classes enfants.
    protected string $fullName;

//------------------------------------- Méthodes. Méthodes publiques = API de l'objet/Services offerts

  //Constructeur : méthode appelée automatiquement quand vous faites new()
    public function __construct(string $fullName = "Jane Doe")
    {
        echo "Un nouvel objet a été crée" . PHP_EOL;
        $this->fullName = $fullName;
    }

  //Méthode = fonction appartenant à un objet et pouvant travailler sur ses propriétés
    public function showDetailsHML()
    {
      //Uné méthode peut accéder aux attributs (variables) de la classe.
      //Référence vers l'instance de la classe : $this
        echo "<h1 class='strong'>{$this->fullName}</h1>";
    }
}

//Code client (consommateur d'objets)
//Créer un objet = instancier une classe //Intialiser l'objet dans un état correct

$user = new User("John Doe");
 var_dump($user);
 //Envoyer un message a l'objet = appeler une de ses méthodes
 $user->showDetailsHML();

 //La propriété est privée : peut pas y accéder depuis l'exterieur (lecture/ecriture) //La propriété est public : accessible depuis l'exterieur de l'objet (lecture/ecriture)
 $user->fullName = "JANE";
echo $user->fullName ;
 //Accéder a une constante de classe (via operateur portée)
 echo User::FORMAT;

 //Exemple de classe ne possédant que des propriétés statiques (de classe) = espace de noms pour des fonctions.

class Utils
{
    public static function doX()
    {
    };
    public static function doY()
    {
    };
}

//On peut appeler des méthodes static ('de classe') sans instancier d'objets
Utils::doX();
Utils::doY();

/**
 * Héritage : Généralisation/Spécialisation et partage de code
 */

//Spécialisation d'une classe. Par ex, j'ai un User. J'ai différents types d'utilisateurs : admin, visiteur
//Admin est une classe qui 'hérite' de User.
//Étend = recupere toutes les fonctionnalités d'User (attributs et méthodes) + ajouter comportement spécifique aux admins.
//Admin est une sous-classe ou une classe enfant de User (types)
class Admin extends User
{
  //Hérite de tous attributs + methodes (__construct, showDetailHTML, fullName)
  //1. Etendre : ajouter d'autres méthodes/attributs spécifiques aux admin
    public function doAdminStuff()
    {
        echo "Doing admin stuff..." . PHP_EOL;
    }

  //2. Surcharger (override) = redéfinir l'implémentation d'une méthode existante chez le parent (User)
    public function showDetailsHML()
    {
      //Appelle la méthode du parent d'abord
      //parent:: acceder aux méthodes/attributs de la classe parente
        parent::showDetailsHML();
        echo "<h2>Role: Admin</h2>";
    }
}

$admin = new Admin();
var_dump($admin);
$admin->showDetailsHML();
