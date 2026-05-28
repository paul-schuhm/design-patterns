# Partie 4/4 Problèmes à résoudre avec des *Design Patterns*

> Dans les exercices et la littérature, le code "*client*" ou tout
> simplement "*client*" **est le code qui va utiliser votre code**,
> manipuler et consommer vos classes.

- [Partie 4/4 Problèmes à résoudre avec des *Design Patterns*](#partie-44-problèmes-à-résoudre-avec-des-design-patterns)
  - [Problème 1 : Singleton](#problème-1--singleton)
  - [Problème 2 : *Factory Method*/*Virtual constructor*](#problème-2--factory-methodvirtual-constructor)
  - [Problème 3 *Abstract Factory*](#problème-3-abstract-factory)
  - [Problème 4 *Builder*](#problème-4-builder)
  - [Problème 5 : *Adapter*](#problème-5--adapter)
  - [Problème 6 : *Observer*](#problème-6--observer)
  - [Problèmes supplémentaires](#problèmes-supplémentaires)

## Problème 1 : Singleton

**Implémentez** une classe `Database` fournissant un accès à une base de
données en utilisant le *Design Pattern* *Singleton*.

> On simulera la connexion ici.

La connexion aura les attributs `host`, `user`, `password`, `dbname`,
`port` et la méthode `instance` qui renverra l'instance *unique* de la
classe.

1. **Instancier** une connexion. Vous obtiendrez une
    `Fatal Error: Uncaught PDOException`, *c'est normal* car nous n'avez
    pas mis en place de base de données. **Utiliser** un bloc
    `try/catch` sur la `PDOException` pour récupérer l'erreur de
    connexion et afficher un message d'erreur (*fails gracefully*).

2. **Écrivez un test** pour montrer qu'il n'y a bien qu'une seule
    instance de `Connection` à l'exécution.

> Indication: Le DSN d'une base de données MySQL est de la forme
> `mysql:host=localhost;port=3307;dbname=testdb`
>
> Indication: Le module
> [PDO](https://www.php.net/manual/fr/intro.pdo.php)(*PHP Data Object*)
> est une interface d'accès à des SGBD fournie avec PHP. Exemple
> d'utilisation pour initialiser une connexion à une base de données:
> `new PDO($dsn, $user, $password)`

> PDO sera abordé dans la partie 4

## Problème 2 : *Factory Method*/*Virtual constructor*

On propose de réaliser un mini framework qui permet de faire des
`Application`s pouvant présenter à l'écran différents types de
`Document`. Un document doit pouvoir être *ouvert*, *fermé*, sauvé*,
modifié*.

> L'implémentation des méthodes correspondantes pour `open`, `close`,
> `save` ou `modify` de `Document` ne sont pas demandées, un simple
> message sur la sortie standard du type
> `echo 'Le document est ouvert en lecture et en écriture'` pour la
> méthode `open()` est suffisant. Vous pouvez toujours implémenter une
> solution complète si vous avez le temps ou si cela vous intéresse.

Ci-dessous vous est présenté un diagramme UML du projet. Le framework
met à disposition les classes `Application` et `Document`.

Un·e utilisateur·rice du framework peut ensuite définir une classe
`MyApplication` de type Application et `MyDocument` type de `Document`
et développer son propre projet.

`<img src="exercice2.2-uml.png" width="600">`{=html}

[Utilisez le pattern Factory
Method](https://refactoring.guru/design-patterns/factory-method) (aussi
nommé *Virtual Constructor*) pour implémenter ce framework.

1. **Créer** les classes `Application` et `Document` mises à
    disposition par le framework.
2. **Proposer** une *implémentation* de ce framework et **implémenter**
    deux applications: `ApplicationImage` qui manipule un document
    `Image` et `ApplicationText` qui manipule un document `Texte`.
3. Pour le document image `DocumentImage`, **implémenter une solution
    demandant** à l'utilisateur de rentrer les coordonnées de l'image
    (afficher juste un message sur la sortie standard) *avant* de créer
    le document.

> Indication: Le framework ne peut pas savoir à l'avance quel document
> il va manipuler (cela peut être un document image, un document texte,
> etc.)

## Problème 3 *Abstract Factory*

Nous disposons d'un kit de *widgets* pour construire des interfaces
utilisateur qui supporte plusieurs *look and feel* standards (par ex.
Windows, MacOSX, Android, etc.).

Chaque *look-and-feel* définit des apparences et des comportements
différents pour chaque Widget. Pour que notre framework soit
*cross-plateform*, nous devons l'implémenter de sorte que les
utilisateurs puissent facilement développer leurs propres widgets.

Nous allons donc mettre en place un *toolkit* de Widgets `Scrollbar`,
`Window` et `Button` qui supporteront chacun plusieurs *look and feel*.

**Utiliser le [Design Patern *Abstract
Factory*\*\*](https://refactoring.guru/design-patterns/abstract-factory)
pour *créer* des familles (`Scrollbar`, `Window` et `Button`) de Widgets
au *look-and-fill* `Windows`, `MacOSX` et `Android`.**Afficher\*\*
chaque famille de Widgets sur la sortie standard en surchargeant leur
méthode `toString()`. On affichera les propriétés des Widgets et leurs
valeurs associées.

> On définira le *look-and-feel* de la manière suivante:
>
> - Window: `border-radius` (int), `border-color` (string)
> - Button: `bg-color` (string), `bg-color--hover` (string)
> - Scrollbar: `scrollbar-width` (Enum: thin, auto, none)

> Windows: border-radius=2, border-color='blue', bg-color='blue',
> bg-color--hover='dark-blue'

> MacOSX: border-radius=1, border-color='grey', bg-color='white',
> bg-color--hover='grey'

> Android: border-radius=0, border-color='black', bg-color='green',
> bg-color--hover='purple'

## Problème 4 *Builder*

Nous devons développer une application web pour une administration dans
laquelle nous aurons besoin de nombreux formulaires avec un *grand
nombre de* *champs*.

Chaque *champ* est caractérisé par:

- un `type`: `text`, `number`, `email`, `password` ou `select`
- un `name`: le nom du champ qui sera manipulé par le programme
- un `label`: un libellé à afficher à côté de l'input

Pour le moment nous avons deux formulaires à créer.

**Le formulaire A** se compose des champs obligatoires suivants :

- **Label**: Prénom, **Type**: text, **Name**: `firstname`
- **Label**: Nom, **Type**: text, **Name**: `lastname`
- **Label**: E-mail, **Type**: email, **Name**: `email`
- **Label**: Numéro de sécurité sociale, **Type**: number, **Name**:
    `socialnumber`
- **Label**: Situation familiale, **Type**: select, Choix: Marié·e,
    Pacsé·e, Divorcé·e, Célibataire, Veuf·ve, **Name**:`status`
- **Label**: Adresse 1, **Type**: text, **Name**: `address1`
- **Label**: Adresse 2, **Type**: text, **Name**: `address2`
- **Label**: Code postal, **Type**: text,**Name**: `zipcode`
- **Label**: Ville, **Type**: text, **Name**: `city`

**Le formulaire B** se compose des champs obligatoires suivants :

- **Label**: Votre E-mail, **Type**: email, **Name**: `email`
- **Label**: Confirmation E-mail, **Type**: email, **Name**:
    `email_check`

Ces formulaires *sont amenés à être modifiés et d'autres formulaires
devront être ajoutés* pour la version 2 de l'application.

Pour faire face à la complexité de création, validation des formulaires
et gérer leurs variations, [**utiliser le pattern
Builder**](https://refactoring.guru/design-patterns/builder) pour créer
ces 2 formulaires, et **afficher les sur une page web**.

## Problème 5 : *Adapter*

Vous développez une application qui utilise actuellement une interface
`Notification` dotée d'une méthode `send()` :

``` php
//Interface utilisée dans le système
interface Notification
{
    public function send(string $title, string $message): void;
}
```

Jusqu'ici, le système fonctionne parfaitement pour les envois classiques
(e-mails, SMS).

Vous devez intégrer un nouveau canal de communication : Slack.
Cependant, l'équipe Slack fournit une bibliothèque tierce (*vendor*)
avec sa propre classe `SlackApi` et une méthode
`postMessage(channel, text)`. Les signatures ne correspondent *pas*, et
il est i**mpossible de modifier le code de la bibliothèque Slack**.

[En utilisant le Design Pattern
*Adapter*](https://refactoring.guru/design-patterns/adapter), proposez
une solution technique permettant d'envoyer des notifications sur
*Slack* en passant par l'interface existante `Notification`, **sans
modifier le code client actuel**, ni la classe SlackApi ci-dessous.

``` php
class SlackApi
    {
        private $login;
        private $apiKey;

        public function __construct(string $login, string $apiKey)
        {
            $this->login = $login;
            $this->apiKey = $apiKey;
        }

        public function logIn(): void
        {
            // Send authentication request to Slack web service.
            echo "Logged in to a slack account '{$this->login}'.\n";
        }

        public function postMessage(string $channel, string $text): void
        {
            // Send notification message (post request to Slack web service).
            echo "Posted following message on the '$channel' : '$message'.\n";
        }
    }

    class Client
    {
        function send(Notification $notification, string $title, string $message)
        {
            $notification->send($title, $message);
        }
    }
```

## Problème 6 : *Observer*

> Notions abordées : gestion inputs utilisateurs, design pattern
> Observer, poo, manipulation de fichiers, json, xml

1. **Créer** un programme `list` capable de représenter des personnes.
    Chaque personne à un nom de famille et une date de naissance. Le
    programme, une fois exécuté, attend un input utilisateur (via la
    CLI). L'utilisateur renseigne un nombre. Lorsqu'il le reçoit, le
    programme doit afficher toutes les personnes ayant un âge supérieur
    à ce nombre et les classer du plus jeune au plus âgé. Pour réaliser
    ce programme, [implémenter le pattern
    Observer](https://refactoring.guru/fr/design-patterns/observer).

Soit *Foo*, *Bar* et *Baz*, trois personnes nées respectivement le
17/03/1978, 25/02/2002 et le 27/11/2014. Voici le résultat attendu par
le programme :

``` bash
./list 18
Bar
Foo 
```

2. Si l'*input* utilisateur est une chaîne de caractère, le programme
    doit afficher toutes les personnes dont le nom commence par la
    chaîne de caractère et les classer par ordre alphabétique. Il ne
    doit pas être sensible à la casse.

3. **Bonus** : **Imprimer** la liste générée dans deux fichiers
    `list.json` et `list.xml`, sauf si la liste est vide.

``` bash
./list b
```

Sortie attendue :

``` json
//list.json
[
    {
        "name": "Bar",
        "birthDate": "25/02/2002"
    },
     {
        "name": "Baz",
        "birthDate": "27/11/2014"
    }
]
```

``` xml
<!-- list.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<results>
    <person birthDate="25/02/2002">
      <name>Bar</name>
    </person>
    <person birthDate="27/11/2014">
      <name>Baz</name>
    </person>
</results>
```

## Problèmes supplémentaires

Explorez d'autres *Design Patterns* :

- Le Pattern
    [*Decorator*](https://refactoring.guru/fr/design-patterns/decorator)
- Le Pattern
    [*Facade*](https://refactoring.guru/fr/design-patterns/facade)

Vous pouvez trouver des exercices supplémentaires ici:

- [Exercices de design pattern en
    php](https://github.com/owalid/php_desing_pattern_exercises)
- [Écrivez du PHP maintenable avec les principes SOLID et les design
    patterns
    (OpenClassRoom)](https://openclassrooms.com/fr/courses/7415611-ecrivez-du-php-maintenable-avec-les-principes-solid-et-les-design-patterns/7419805-quest-ce-quun-design-pattern)
- [Exemples de patrons de conception dans les différents langages de
    programmation](https://refactoring.guru/fr/design-patterns/examples)
- [CodeWars: Kata Design Patterns (tous langages
    confondus)](https://www.codewars.com/kata/search/?q=&tags=Design%20Patterns&order_by=sort_date%20desc)
