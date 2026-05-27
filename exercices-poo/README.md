# Partie 1/4 Problèmes à résoudre en Programmation Orientée Objet

- [Partie 1/4 Problèmes à résoudre en Programmation Orientée Objet](#partie-14-problèmes-à-résoudre-en-programmation-orientée-objet)
  - [Problème 1 : Héritage simple](#problème-1--héritage-simple)
  - [Problème 2 : les précautions à prendre avec l'héritage](#problème-2--les-précautions-à-prendre-avec-lhéritage)
  - [Problème 3 : Interfaces, polymorphisme et injection de dépendances](#problème-3--interfaces-polymorphisme-et-injection-de-dépendances)
  - [Problème 4 : Interfaces et implémentations de structures de données abstraites *Queue* et *Stack*](#problème-4--interfaces-et-implémentations-de-structures-de-données-abstraites-queue-et-stack)
    - [Liens utiles](#liens-utiles)
  - [Problème 5 : passage par copie et passage par référence](#problème-5--passage-par-copie-et-passage-par-référence)
  - [Problème 6 : Passage par copie, passage par référence et clonage](#problème-6--passage-par-copie-passage-par-référence-et-clonage)
  - [Problème 7 : L'API Reflection et les attributs PHP](#problème-7--lapi-reflection-et-les-attributs-php)
  - [Mini-projet orienté objet](#mini-projet-orienté-objet)
  - [Exercices supplémentaires (et corrigés)](#exercices-supplémentaires-et-corrigés)

## Problème 1 : Héritage simple

> Notions abordées: classe, objet, attribut(propriété), méthode,
> constructeur, héritage, `parent::`, `static`

1.  **Créer** une classe `City` représentant une ville avec les
    attributs `name` et `county` (département). On la placera dans son
    fichier `City.php`. **Utiliser** un *code client* (un script PHP,
    `index.php` par exemple) pour créer des instances avec différentes
    valeurs d'attribut et utiliser [leur méthode d'affichage
    `__toString()`](https://www.php.net/manual/en/stringable.tostring.php)
    pour les imprimer sur la sortie standard sous la forme
    `"La ville X est dans le département Y"`.
2.  **Créer** une classe nommée `CityWithArea` (*area* désigne la
    région) qui étend la classe `City` affichant
    `"La ville X est dans le département Y de la région Z"`.
3.  **Modifier** la classe `City` pour que l'on puisse **connaître la
    ville ayant le nom le plus long**.

On décide d'enrichir notre programme car le modèle de *région* doit être
plus complexe (car le monde est complexe !).

> Nous allons faire de la composition (*aggregation*) d'objets !

4.  **Créer** une classe `Area` (région) dans `Area.php` avec les
    attributs `name` et `code`. Tous ses attributs **doivent être
    privés**.
5.  **Créer** une classe `County` (département) dans `County.php` avec
    les attributs `name`, `code`, et `area` (qui sera une instance de la
    classe `Area`). Tous ses attributs **doivent être privés**.
6.  **Modifier** la classe `City` pour que son attribut `county` ne soit
    plus une simple chaîne de caractères, mais une instance de la classe
    `County`.
7.  **Adapter** la méthode `__toString()` de `City` pour qu'elle navigue
    à travers ses objets composants afin d'afficher :
    `"La ville X est dans le département Y (Code) de la région Z"`.
    Comment faire si les attributs sont privés ?
8.  **Utiliser** [la syntaxe moderne de
    PHP](https://www.php.net/manual/fr/language.oop5.decon.php#language.oop5.decon.constructor.promotion)
    pour déclarer et initialiser tes attributs directement dans les
    arguments du constructeur (*Promotion du constructeur*), ce qui rend
    le code plus simple à lire et modifier.

## Problème 2 : les précautions à prendre avec l'héritage

> Ce problème est un *classique* de la POO, on verra pourquoi.

On développe le moteur d'un logiciel de dessin vectoriel (type Figma ou
Illustrator). L'utilisateur peut sélectionner des formes et modifier
leurs dimensions à la souris via un panneau de propriétés.

1.  **Créez** une classe `Rectangle` disposant de :

-   Deux propriétés : `width` (largeur) et `height` (hauteur) en pixels
-   Une méthode `setWidth(int \$width): void` et `setHeight(int \$height): void`
    permettant de redimensionner la forme de manière dynamique
-   Une méthode `area(): int` qui retourne l'aire du rectangle, en $$pixels\^2$$

> L'utilisateur de la classe doit pouvoir créer des objets avec les
> dimensions qu'il désire. On souhaite à présent créer une classe
> `Square` qui étend `Rectangle` (un carré est juste un cas particulier
> de rectangle !).

2.  **Implémentez** la classe `Square` : elle étend `Rectangle` et
    surcharge (*override*) `setWidth` et `setHeight` pour s'assurer que
    si l'on modifie la largeur, la hauteur prend la même valeur (et
    vice-versa), afin de préserver la *nature* du carré. Remarquez-vous
    un problème ?
3.  **Créez** une fonction globale `stretchHorizontal` dans le logiciel
    qui permet d'étirer horizontalement n'importe quel rectangle
    sélectionné pour lui donner une largeur spécifique, *sans toucher à
    sa hauteur*. Voici sa signature

``` php
function stretchHorizontal(Rectangle $r, int $newWidth): void
```

4.  Voici un extrait de code manipulant ces abstractions :

``` php
// 1. Test avec un vrai Rectangle de 4x5
$rect = new Rectangle(4, 5);
stretchHorizontal($rect, 10);
echo "Aire du rectangle attendue : 200 (4 * 10 * 5) | Obtenue : " . $rect->area() . "\n";

// 2. Test avec un Square de 4x4 (qui est censé être substituable à Rectangle)
$square = new Square(4); // largeur = 4, hauteur = 4
stretchHorizontal($square, 10);
echo "Aire du carré attendue : 160 (4 * 10 * 4) | Obtenue : " . $square->area() . "\n";
```

**Qu'observez-vous ?**

> En mathématiques, un carré *est* un rectangle. Mais en programmation
> orientée objet, la relation "Est un" (Is-A) ne valide pas
> *automatiquement* l'héritage. L'héritage exige que **l'enfant respecte
> le comportement du parent dans tous les scénarios possibles** (c'est
> le [Principe de Substitution de Liskov, le "L" de *SOLID*. Lire l'excellente section
> "Exemple de violation du
> LSP"](https://fr.wikipedia.org/wiki/Principe_de_substitution_de_Liskov)).
> Si le comportement diverge (comme ici lors d'une modification de
> dimension), il faut **abandonner l'héritage et passer par une
> Interface**.

5.  En POO, hériter pour *gagner du code* est souvent une *fausse bonne
    idée*. Un carré n'a pas besoin de posséder une largeur **et** une
    hauteur en mémoire. **Réimplémentez** ce système de manière à ce que
    `Rectangle` et `Square` soient *deux* entités totalement
    indépendantes, tout en permettant au logiciel de les manipuler
    ensemble dans une même collection grâce à *un contrat commun* :
    1.  Définissez une **interface** (nommée par exemple `Shape`) qui
        servira de dénominateur commun. Cette interface **définit** les
        méthodes :
        -   `area(): float` : calcule et retourne l'aire de la forme
        -   `perimeter(): float` : calcule et retourne le périmètre de
            la forme
        -   `stretchHorizontal(double factor): Shape` : retourne une
            nouvelle instance Rectangle avec les nouvelles dimensions
            (étirement horizontal)
        -   `stretchVertical(double factor): Shape` : retourne une
            nouvelle instance Rectangle avec les nouvelles dimensions
            (étirement horizontal)
        -   `rescale(double factor) : Shape` : retourne une nouvelle
            instance de Rectangle ou de Square, avec les dimensions mise
            à l'échelle
    2.  **Revisitez** vos classes `Rectangle` et `Square` de manière à
        ce qu'elles implémentent toutes les deux l'interface `Shape`,
        créée précédemment
    3.  *Code client* : **créez** une collection (un tableau) de
        rectangles et de carrés. Pour chaque élément de la collection :
        1.  **Affichez** leur surface
        2.  **Procédez** à un étirement horizontal d'un facteur `4.2`
6.  Si l'on veut ajouter une nouvelle forme (par exemple un triangle),
    **que faudra-t-il faire** ? **Est-ce que le code client doit être
    modifié** (le code qui manipule et consomme les formes géométriques)
    ?
7.  Si l'on veut ajouter à présent une forme de *cercle*, est-ce que
    notre interface `Shape` est adaptée ?
8.  *Immutabilité* : quel est l'intérêt de ne pas *muter* les objets (de
    *mettre à jour* leurs états) mais de retourner une nouvelle instance
    à la place lorsqu'on les modifie ?

## Problème 3 : Interfaces, polymorphisme et injection de dépendances

Vous travaillez sur le module d'achat d'un site e-commerce. Lorsqu'un
client valide son panier, la classe `UseCaseProcessOrder` doit :

-   enregistrer la commande ;
-   **avertir** l'utilisateur que son achat est confirmé.

Au départ, l'application envoyait uniquement des *e-mails*. Mais le
marketing veut maintenant pouvoir envoyer des *SMS* pour les commandes
urgentes, ou des *notifications Push* pour les utilisateur·ices de
l'application mobile.

Vous devez concevoir ce système pour que `UseCaseProcessOrder` puisse
envoyer ces différents types de notifications, **sans que l'on ait à
modifier une seule ligne de sa classe** (à l'avenir, un nouveau type de
notification sera sûrement demandé !)

1.  **Créez** l'interface `NotifierInterface`. Elle doit *définir* la
    méthode requise pour envoyer un message :

``` php
public function send(User $user, string $message): bool;
```

2.  **Créez** trois classes qui **implémentent** `NotifierInterface`.
    Pour l'exercice, elles se contenteront d'un `echo` sur la sortie
    standard pour simuler l'envoi :
    -   `EmailNotifier` : Affiche " \[Email\] Envoyé à \[email de
        l'user\] : \[message\] "
    -   `SmsNotifier` : Affiche " \[SMS\] Envoyé au \[téléphone de
        l'user\] : \[message\] "
    -   `PushNotifier` : Affiche " \[Push Notification\] Notification
        interne : \[message\]"

Il faut à présent fournir une implémentation à `UseCaseProcessOrder`
pour envoyer la notification. Pour cela, nous allons réaliser *une
injection de dépendance* (*Dependency injection* ou DI) *via* le
constructeur

3.  **Créez** la classe `UseCaseProcessOrder`. Elle doit :
    1.  **Recevoir une instance** d'un objet de type `NotifierInterface`
        *via* son constructeur (*Injection de dépendance*).**Elle ne
        doit jamais instancier un formateur elle-même** (pas de
        `new EmailNotifier()` à l'*intérieur* du constructeur.) Pourquoi
        ?
    2.  **Posséder** une méthode
        `completeOrder(User $user, float $amount): void`. Cette méthode
        *simule* la validation de la commande, puis **utilise le
        notifier** injecté pour envoyer le message suivant :
        `"Merci pour votre commande de [amount] EUROS !"`. Voici un
        *template* de code pour vous guider :

``` php
class UseCaseProcessOrder {
    // À FAIRE : Injecter le NotifierInterface par le constructeur
    
    public function completeOrder(User $user, float $amount): void {
        // Simule le traitement de la commande
        echo "- Traitement de la commande de {$user->name} ({$amount} euros)" . PHP_EOL;
        // Simule la persistance/mise à jour du status de la commande en base de données
        echo "- Commande enregistrée avec succès" . PHP_EOL;
        // À FAIRE : déclencher la notification **via la dépendance injectée**
    }
}
```

Voici une classe `User` utilisable pour votre programme :

``` php
// Classe représentant un User ("Value object") : aucune méthode, une 'map' qui **transporte de l'information**. Notez la directive readonly.
readonly class User {
    public function __construct(
        public string $fullName,
        public string $email,
        public string $phoneNumber
    ) {}
}
```

4.  **Écrire** un code client pour utiliser votre système. Voici un
    scénario :
    1.  Créer un User (récupérer en base de données)
    2.  Préparer une notification par email
    3.  Créer une instance du *use-case*
    4.  Exécuter l'*use-case* pour l'user crée

``` php
//Scénario
$client = new User("Jane Doe", "jdoe@email.com", "0612345678");
$processOrder = new UseCaseProcessOrder(_________); // DI
$processOrder->completeOrder(_________);
```

5.  Si demain on doit ajouter un `WhatsappNotifier`, quelles classes
    existantes doit-on modifier ?
6.  Pourquoi l'*injection de dépendance* facilite-t-elle l'écriture de
    *tests* pour `UseCaseProcessOrder` ? (*Indice : Pensez aux faux
    objets/Mocks*).

## Problème 4 : Interfaces et implémentations de structures de données abstraites *Queue* et *Stack*

1.  Dans un fichier `ds.php`, **créer** deux classes :

-   `Queue` qui implémente `QueueInterface` [(comportement *FIFO* :
    First In, First
    Out)](https://en.wikipedia.org/wiki/Queue_(abstract_data_type)).
-   `Stack` qui implémente `StackInterface` [(comportement *LIFO* : Last
    In, First
    Out)](https://en.wikipedia.org/wiki/Stack_(abstract_data_type)).

2.  **Créez** un fichier `interfaces.php` contenant les deux contrats
    (`interfaces`) que les classes devront respecter. Voici les
    interfaces de ces structures de données :

-   **Queue** :
    -   enqueue() : ajoute un élément de la queue
    -   dequeue() : retire un élément de la queue. Lève une exception de
        type
        [UnderflowException](https://www.php.net/manual/en/class.underflowexception.php)
        si la file est vide.
    -   size() : donne le nombre d'éléments restants dans la queue
-   **Stack** :
    -   push() : ajoute un élément à la stack
    -   pop() : retire un élément de la stack
    -   isEmpty() : renvoie vrai si la stack est vide, faux sinon
    -   size() : donne le nombre d'éléments dans la queue

**Complétez** les signatures de ces méthodes avec le *type hinting*,
puis faites **implémenter** ces interfaces par vos classes `Queue` et
`Stack`.

3.  Une fois les classes écrites, **testez vos implémentations**
    (Queue/Stack) et la gestion des erreurs avec le script ci-dessous :

``` php
<?php
//Fichier de test.
//Inclure ici les interfaces et les classes (vos fichiers ds.php et interfaces.php)

echo "--- ÉVALUATION DE LA STACK (LIFO) ---" . PHP_EOL;
try {
    $stack = new Stack();
    $stack->push("A");
    $stack->push("B");
    $stack->push("C");

    echo "Taille attendue (3) : " . $stack->size() . "\n";
    echo "Retiré attendu (C) : " . $stack->pop() . "\n";
    echo "Retiré attendu (B) : " . $stack->pop() . "\n";
    echo "Est-elle vide ? (false) : " . ($stack->isEmpty() ? 'true' : 'false') . "\n";
    echo "Retiré attendu (A) : " . $stack->pop() . "\n";
    echo "Est-elle vide ? (true) : " . ($stack->isEmpty() ? 'true' : 'false') . "\n";
    
    // Doit lever une exception
    $stack->pop(); 
} catch (UnderflowException $e) {
    echo "Succès : Exception correctement capturée pour la Stack vide !" . PHP_EOL;
} catch (Exception $e) {
    echo "Erreur : Mauvais type d'exception levé" . PHP_EOL;
}

echo "\n--- ÉVALUATION DE LA QUEUE (FIFO) ---" . PHP_EOL;
try {
    $queue = new ArrayQueue();
    $queue->enqueue("X");
    $queue->enqueue("Y");
    $queue->enqueue("Z");

    echo "Taille attendue (3) : " . $queue->size() . "\n";
    echo "Retiré attendu (X) : " . $queue->dequeue() . "\n";
    echo "Retiré attendu (Y) : " . $queue->dequeue() . "\n";
    echo "Retiré attendu (Z) : " . $queue->dequeue() . "\n";
    
    // Doit lever une exception
    $queue->dequeue();
} catch (UnderflowException $e) {
    echo "Succès : Exception correctement capturée pour la Queue vide !\n";
} catch (Exception $e) {
    echo "Erreur : Mauvais type d'exception levé.\n";
}
```

4.  PHP fournit des structures de données abstraites usuelles comme la
    [SplStack](https://www.php.net/manual/fr/class.splstack.php) et la
    [SplQueue](https://www.php.net/manual/fr/class.splqueue.php), via
    [le module SPL (Standard PHP
    Library)](https://www.php.net/manual/fr/book.spl.php), crée en 2009
    (PHP 5.3). Ces structures de données sont fournies sous forme de
    classes :
    1.  **Inspecter** l'interface du type
        [SplStack](https://www.php.net/manual/fr/class.splstack.php).
        Est-elle *cohérente* ? Pourquoi ? De même avec l'interface de
        [SplQueue](https://www.php.net/manual/fr/class.splqueue.php) ?
    2.  [Un nouveau module de Data Structures
        (Ds)](https://www.php.net/manual/fr/book.ds.php) a été développé
        en 2016 (par Rudi Theunissen). **Pourquoi est-il préférable
        d'utiliser les structures de données de ce module** (*Ds*)
        plutôt que celles fournies par la *Spl* ?

### Liens utiles

-   [Le module Data Structures Ds de
    PHP](https://www.php.net/manual/fr/book.ds.php)
-   [Les structures de données en PHP - Frédéric BOUCHERY - AFUP Day
    2020
    Nantes](https://www.youtube.com/watch?v=tX1jbqnjrR0&list=PLS3XEhTy6-Ale8Et6pxRR2I3LYNt8-rX3&index=84)

## Problème 5 : passage par copie et passage par référence

Voici un extrait de code où des fonctions manipulent un objet :

``` php
<?php

class Foo{
  public function __construct(public int $a = 0){}
}

function updateObject1(Foo $foo): void{
  $foo = new Foo(1);
}

function updateObject2(Foo $foo): void{
  $foo->a = 2;
}

function updateObject3(Foo &$foo): void{
  $foo = new Foo(3);
}

$obj = new Foo();

updateObject1($obj);
echo $obj->a . PHP_EOL;
updateObject2($obj);
echo $obj->a . PHP_EOL;
updateObject2($obj);
echo $obj->a . PHP_EOL;
```

Quelle sortie va produire ce programme ? **Pourquoi** ?

**Même question** pour ce code où des fonctions manipulent un tableau
(`Array`) :

``` php
<?php

function updateArray1(array $arr){
  $arr[] = 1;
}

function updateArray2(array &$arr){
  $arr[] = 2;
}

$arr = [0];

updateArray1($arr);
var_dump($arr);
updateArray2($arr);
var_dump($arr);
```

## Problème 6 : Passage par copie, passage par référence et clonage

On gère un système de paniers d'achat (`Cart`) qui contiennent des
articles (`Product`). On souhaite appliquer une réduction temporaire sur
un produit pour un utilisateur spécifique, sans impacter le prix
catalogue du produit pour les autres utilisateurs.

Voici le code de départ :

``` php
<?php

class Product {
    public function __construct(
        public string $name,
        public float $price
    ) {}
}

class Cart {
    private array $products = [];

    public function addProduct(Product $p): void {
        $this->products[] = $p;
    }

    public function showContent(): void {
        //Affiche le contenu du panier, à implémenter !
    }
}
```

1.  **Copiez/collez** le code dans un fichier `index.php`.
2.  **Écrivez** un script qui déroule le scénario suivant :
    1.  **Créez** un produit catalogue : Un MacBook Pro à 2000 EUROS.
        Gardez le en mémoire dans la variable `$productMacBook`.
    2.  **Créez** le panier de *John*. **Ajoutez**-y ce MacBook Pro.
    3.  **Créez** le panier de *Jane*. Jane dispose d'un code promo
        secret qui lui donne droit à 50% de réduction sur le MacBook
        Pro. Pour le panier de *Jane*, **affectez le MacBook Pro à une
        nouvelle variable** : `$productMacBookJane = $productMacBook`.
        **Modifiez** son prix à `1000` EUROS, puis **ajoutez**
        `$productMacBookJane` au panier de *Jane*.
    4.  **Affichez** le contenu du panier de *John* et le contenu du
        panier de *Jane* (avec la méthode `showContent()`).
    5.  **Qu'observez-vous** ? Quel est le prix du MacBook dans le
        panier de *John* ? Pourquoi ?
    6.  En PHP, les variables contenant des objets sont-elles passées
        *par valeur* (copie) ou *par référence* (pointeur) ?
3.  **Modifiez** votre script [en utilisant le mécanisme de clonage de
    PHP
    (`clone`)](https://www.php.net/manual/fr/language.oop5.cloning.php)
    pour que *Jane* bénéficie de ses 1000 EUROS de réduction sans
    impacter le panier de *John* (qui doit rester à 2000 EUROS).
4.  Sur le clonage : quelle est la **différence** entre la **copie en
    profondeur** (*deep copy*) ou la **copie en surface** (*shallow
    copy*) dans le processus de **clonage d'objets** ? Par exemple, si
    `Product` a une référence vers une `Category`, est ce que le produit
    cloné partagera la même instance de catégorie que le produit initial
    ? Comment doit-on s'y prendre si l'on veut que `Product` dispose de
    sa propre instance de `Category` ?

## Problème 7 : L'API Reflection et les attributs PHP

[L'API Reflection](https://www.php.net/manual/en/book.reflection.php)
est le pilier de la **méta-programmation** en PHP. C'est grâce à cette
API que les frameworks/tooling lisent les attributs/annotations (comme
`#[Route('/path')`\]) pour lier une URL à une méthode de votre code sans
que vous n'ayez besoin de configurer manuellement un routeur centralisé.

> métaprogrammation : technique de programmation informatique permettant
> à un programme de **traiter d'autres programmes, ou lui-même, comme
> leurs données (inputs)**. Un programme peut ainsi se modifier
> lui-même, parfois pendant qu'il est en train de s'exécuter. Certains
> langages, comme la famille de Lisp, font de la métaprogrammation *par
> design* (le code est une donnée comme une autre)

Voici un code métier "inconnu" que votre script va devoir inspecter et
manipuler *dynamiquement*.

``` php
<?php
// Une classe d'exemple représentant un contrôleur dans une application
class UserController 
{
    private string $prefix = "API_";
    public function __construct(
        private string $defaultRole = 'guest'
    ) {}

    /**
     * Une méthode d'action (méthode publique, avec effets de bord)
     */
    //Un attribut PHP
    #[Route('/users')]
    public function indexAction(int $page = 1, string $sort = 'desc'): string 
    {
        //Appelle une source de données (db...)
        return "Liste des utilisateurs :  Page : $page, Tri : $sort";
    }
    /**
     * Une autre méthode d'action (écrit sur stdout)
     */
    #[Route('/users/:id')]
    public function showUserAction(int $id): string 
    {
        echo "Affichage de l'utilisateur numéro : $id" . PHP_EOL;
    }
    /**
     * Une méthode interne (ne doit *pas* être listée comme une action)
     */
    private function logRequest(): void 
    {
        // Log interne...
    }
}
```

1.  **Créer** une classe `ControllerInspector` dans le fichier
    `ControllerInspector.php` qui prendra le nom d'une classe en
    paramètre et proposera les fonctionnalités suivantes grâce à l'API
    *Reflection* :

-   `getActions(): array` : inspecte la classe et retourne *uniquement*
    le nom des méthodes publiques qui se terminent par le suffixe
    `Action` (ex: `indexAction`, `showAction`).
-   `getMethodParameters(string $methodName): array` : retourne la liste
    des paramètres d'une méthode donnée **avec leur type** (sous forme
    de chaîne de caractères) et indique s'ils ont une valeur par défaut.
-   `executeAction(string $actionName, array $params): mixed` :
    instancie dynamiquement la classe (en passant la valeur `'admin'` au
    constructeur), puis exécute l'action demandée avec les paramètres
    fournis.

2.  **Tester** votre code (classe `ControllerInspector`) avec le script
    ci-dessous :

``` php
<?php
// Importer votre script
// Instanciation de votre inspecteur
$inspector = new ControllerInspector(UserController::class);

echo "Liste des actions publiques : " . PHP_EOL;
$actions = $inspector->getActions();
print_r($actions); 
// Attendu : ['indexAction', 'showUserAction']

echo "Inspection des paramètres de indexAction : " . PHP_EOL;
$params = $inspector->getMethodParameters('indexAction');
print_r($params);
/* Attendu : 
[
    'page' => ['type' => 'int', 'has_default' => true],
    'sort' => ['type' => 'string', 'has_default' => true]
]
*/
echo "Exécution dynamique de showUserAction : " . PHP_EOL;
// Doit instancier UserController avec 'admin' et appeler showUserAction(42)
$result = $inspector->executeAction('showUserAction', ['id' => 42]);
// Attendu : "Affichage de l'utilisateur numéro : 42"
```

3.  Nous voulons ajouter une fonctionnalité qui va inspecter [les
    Attributs
    PHP](https://www.php.net/manual/fr/language.attributes.overview.php)
    (les métadonnées natives du langage, **depuis PHP 8**). Les
    attributs PHP **fournissent des métadonnées structurées et lisibles
    par machine** pour les classes, méthodes, fonctions, paramètres,
    propriétés et constantes. **Ils peuvent être inspectés à l'exécution
    via l'API de réflexion**, **permettant un comportement dynamique
    sans modifier le code**. Les attributs offrent un moyen déclaratif
    d'annoter le code avec des métadonnées.

**Ajoutez** la méthode suivante à votre classe `ControllerInspector` :

``` php
getRouteMapping(): array
```

Cette méthode inspecte toutes les méthodes de la classe. **Si une
méthode possède l'attribut `#[Route]`**, l'inspecteur doit extraire le
chemin (*path*) défini dans l'attribut et l'associer au nom de la
méthode.

Résultat attendu :

``` php
$routes = $inspector->getRouteMapping();
print_r($routes);
/* Attendu : 
[
    '/users' => 'indexAction',
    '/users/:id' => 'showUserAction'
]
```

> C'est par exemple de cette manière que [Symfony détecte les
> routes](https://symfony.com/doc/current/routing.html) ou que Doctrine
> comprend la configuration de la base de données. Avant l'ajout des
> attributs PHP on utilisait [les
> DocBlocks](https://docs.phpdoc.org/guide/getting-started/what-is-a-docblock.html),
> une spécification externe au langage.

## Mini-projet orienté objet

## Exercices supplémentaires (et corrigés)

Si vous avez fini, et souhaitez continuez à pratiquer :

-   Vous pourrez trouver des problèmes supplémentaires sur [codewars
    (challenges PHP en
    POO)](https://www.codewars.com/kata/search/php?q=&tags=Object-oriented%20Programming&beta=false&order_by=sort_date%20desc)
    (il vous faudra seulement vous créer un compte...)
-   Imaginez un mini-projet pour pratique les concepts de la POO et de
    design
-   Demandez-moi !
