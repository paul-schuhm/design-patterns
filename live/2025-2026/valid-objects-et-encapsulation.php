<?php
/*
Discussion sur :

- Les value objects immutables
- La gestion des exceptions (emettre et traiter une exception)
- L'encapsulation, 'ask do not tell !'
- Pattern method factory (avec constructeur privé pour créeer des objets dans un état valide)
*/

//Un autre 'type d'objet rencontré souvent en POO : Le value object. 
//Une simple 'map','dictionnaire', ensemble de clé=>valeurs (comme un objet JavaScript, comme une struct en C, etc.)
$user = [
  'fullName' => 'Jane Doe',
  'birhtDate' => new DateTimeImmutable()
];

//Value objects immutable. Valeurs.Data Transfert Object. POJO, etc.
readonly class User
{
  public function __construct(
    public string $fullName,
    public DateTimeImmutable $birthDate
  ) {}
  //Pas de méthodes
  //Information = faits = valeurs. Pas de comportement !
  //Une information ne se met pas à jour ! Une information ne change pas, c'est une nouvelle information !
}

$user = new User('Jane Doe', new DateTimeImmutable());
//Erreur, objet immutable ! Personne ne peut modifier mon objet une fois crée. Apporte de belles propriétés.
//$user->fullName = 'Evil mutation by some random functions along the way!';
var_dump($user->fullName);

class Circle
{
  //Le constructeur est privé, il faut passer par la factory pour instancier un objet !
  private function __construct(
    private float $radius
  ) {}

  //Method 'factory' (pattern) : retourne une instance valide d'un objet
  public static function create(float $radius): Circle
  {
    if ($radius < 0)
      //Lever une exception (mécanisme 'moderne' pour la gestion des erreurs)
      throw new InvalidArgumentException("Radius should be greater or equal to 0");
    //Reste de la validation...
    //...
    //Une fois l'état initial valide, on retourne une instance
    //self fait référence au nom de la classe
    return new self($radius);
  }

  //Éviter, sur des objets services d'implémenter des getters. Garder les détails d'implémentation cachés (c'est le but d'une abstraction !)
  //Demander à l'objet de **faire quelque chose** au lieu de lui faire exposer ses états/rouages internes !
  //  public getRadius(){
  //    return $this->radius;
  //  }
  //

  //Retourne vrai si le rayon est plus grand que $size, faux sinon.
  public function isRadiusGreaterThan(float $size)
  {
    return $this->radius > $size;
  }

  //Retourne vrai s'il est plus grand que $other, faux sinon
  public function isGreaterThan(Circle $other): bool
  {
    //Une propriété privée peut être accédée par une autre instance de la même classe
    return $this->radius > $other->radius;
  }
}

//Gestion locale d'une exception avec un bloc try/catch
try {
  $circleA = Circle::create(1);
  $circleB = Circle::create(2);
} catch (Exception $e) {
  //Fails gracefully
  echo "Oups, something went wrong... Try again.";
  die;
}

//  A éviter ! ASK (l'objet) DON'T TELL (ne lui demander pas ses infos) !
//  J'ai besoin de savoir son radius, pour... Pourquoi ?
//  Le comparer à un autre cercle par exemple ? Demandez-lui !
//  if($circleA->getRadius() < $circleB->getRadius()){
//
//  }
//  else{
//
//  }

//ASK : on demande à l'objet de faire le travail, je ne veux pas manipuler ses détails internes. IL doit me dire s'il est plus grand qu'un autre cercle.
if ($circleA->isGreaterThan($circleB)) {
  echo "Cercle A est plus grand que Cercle B, il me l'a dit !" . PHP_EOL;
} else {
  echo "Cercle B est plus grand que Cercle A, il me l'a dit !" . PHP_EOL;
}
