<?php

//Implémentation du pattern observer

interface Suscriber{
  public function update($context): void;
}

class ConcreteSuscriber implements Suscriber{
  public function update($context): void{
    echo "Quelque-chose d'intéressant s'est passé ! {$context}" . PHP_EOL;
  }
}
class UserSuscriber implements Suscriber{
  public function update($context): void{
    echo "Quelque-chose d'intéressant s'est passé ! {$context}" . PHP_EOL;
  }
}


class Publisher{

  public function __construct(
      private string $mainState='',
      private array $suscribers=[]
      ){}
  public function suscribe(Suscriber $s){
    //Ajouter une référence de l'abonné à ma liste
    $this->suscribers[] = $s; } public function unsuscribe(Suscriber $s){
    foreach($this->suscribers as $key => $suscriber){
      if($suscriber === $s){
	unset($this->suscribers[$key]);
	return;
      }	
    }
  }
  public function notifySuscribers(){
    foreach($this->suscribers as $s){
      //On notifie les abonnés que l'état a changé
      $s->update($this->mainState);
    }
  }
  //Méthode propre au publisher qui va modifier l'état suivi par les abonnés
  public function mainBusinessLogic(){
    //Etat de l'objet change suite à une procédure métier...
    $this->mainState.='+';
    $this->notifySuscribers();
  }
}

//Client

$publisher = new Publisher();
$suscribers = [new ConcreteSuscriber(), new ConcreteSuscriber(), new UserSuscriber()];

//1. Abonnement
foreach($suscribers as $suscriber){
  $publisher->suscribe($suscriber);
}
//2. Notification après changement 
$publisher->mainBusinessLogic();
$publisher->mainBusinessLogic();

echo "Désabonnement". PHP_EOL;
//3. Désabonnement
foreach($suscribers as $suscriber){
  $publisher->unsuscribe($suscriber);
}
//Devrait rien se passer
$publisher->mainBusinessLogic();

