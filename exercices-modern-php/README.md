# Partie 2/4 : Développement PHP Moderne

- [Partie 2/4 : Développement PHP Moderne](#partie-24--développement-php-moderne)
  - [Utiliser les namespaces PHP](#utiliser-les-namespaces-php)
  - [Problème : développer un service web de génération de fausses données](#problème--développer-un-service-web-de-génération-de-fausses-données)
    - [Développement](#développement)
    - [Qualité](#qualité)

## Utiliser les namespaces PHP

Pour chaque appel, **indiquez** quelle classe ou fonction PHP essaie d'invoquer :

~~~php
<?php
namespace A;
use B\D, C\E as F;

foo();
\foo();
my\foo();
F();        
new B();          
new D();
new F();
new \B();
new \D();   
new \F();             
B\foo();
~~~

> [Exemple issu de la documentation officielle](https://www.php.net/manual/fr/language.namespaces.rules.php)

## Problème : développer un service web de génération de fausses données

On souhaite créer un service web (API) servant des fausses données au format JSON (*dummy data*).

### Développement

1. **Créer** un nouveau répertoire `project` et initialiser un nouveau projet *Composer* avec `composer init`.
2. **Créer** cette structure de projet :

~~~bash
projet/
├── src/ (généré automatiquement)
│   └── Generator.php     <= sources de botre app
├── public/               <= site web
│   └── index.php
├── composer.json (généré automatiquement)
└── vendor/ (généré automatiquement)
~~~

3. Dans le fichier `composer.json`, **configurer** le namespace de premier niveau pour l'*autoloading*. **Nommez** le `App\\`

Votre fichier composer.json doit ressembler à ceci :

~~~json
{
    "name": "votre-nom/project",
    "description": "API de fausses données",
    "type": "project",
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "require": {}
}
~~~

4. **Régénérer** l'*autoloader* (fichier `vendor/autoload.php`) avec la commande `composer dump-autoload`

> Dès que vous modifiez les valeurs de la section `autoload` de `composer.json`, pensez à régénérer l'autoloader !

5. **Installer** le paquet [fakerphp/faker](https://packagist.org/packages/fakerphp/faker) pour générer des fausses données :

~~~bash
composer require fakerphp/faker
~~~

> Prenez le temps d'[explorer un peu ce que ce composant permet de faire](https://fakerphp.org/).

6. **Créer** une classe `Generator` dans les sources de votre projet (`src/`). Cette classe doit :
   1. **Disposer d'une référence** vers le générateur offert par `fakerphp` (`private \Faker\Generator$faker;`)
   2. **Initialiser** le générateur de texte [en locale *française*](https://fakerphp.org/#localization) (dans son constructeur)
   3. **Exposer** une méthode `randomText(int $nbSentences = 1): string` [en utilisant Faker](https://fakerphp.org/). **Placer** votre classe dans le bon *namespace*.
7. **Implémenter** le service web `public/index.php` :

~~~php
require_once __DIR__ . '/../vendor/autoload.php';

//A faire : appeler votre classe Generator et retournez une réponse au format JSON

// Configuration des headers pour indiquer que la réponse est du JSON
header('Content-Type: application/json; charset=utf-8');
//A partir du moment où les headers sont écrits, tout ce qui est écrit sur la sortie sera placée dans le body de la réponse HTTP

//Préparation de la réponse ($response)
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
//Fin du script (envoi de la réponse)
die;
~~~

Il doit retourner les données au format JSON, **avec un code status 200** en cas de succès. Voici un exemple de réponse (*schéma*) :

~~~json
{
  "status": "success",
  "data": {
    "text": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
  }
}
~~~

8. **Lancer** le service web en local avec le serveur interne *built-in* de PHP :

~~~bash
php -S localhost:8080 -t public
~~~

9. **Tester** :

~~~php
curl localhost:8080
~~~

> Tip : [utiliser jq](https://jqlang.org/) (*sed for json*) pour manipuler et formater les documents JSON dans le terminal. Exemple : `curl localhost:8080 | jq`

On souhaite aussi pouvoir utiliser ce générateur *directement en CLI*, pour le composer avec d'autres programmes Unix via le *pipe* (`|`) par exemple.

10. **Créer** un script de console `bin/generate-fakedata` qui affiche la citation directement dans le terminal (en réutilisant vos sources développées précédemment !).
11. **Ajouter** un argument **optionnel** au programme pour lui passer le nombre de phrases à générer. Usage du script :

~~~bash
generate-fake-data [NB_SENTENCES]
~~~

> Notez la syntaxe "UNIX" de la doc, ce qui est entre crochets `[PARAMATER]` est **optionnel**

Par défaut (si `NB_SENTENCES` n'est pas fourni), il génère une seule phrase.

Tip : Sous Unix, pour rendre un script PHP exécutable sans invoquer php vous-même (comme n'importe quel script ou commande du shell), [utiliser l'instruction pré-processor shebang](https://fr.wikipedia.org/wiki/Shebang) qui indique au *shell* quel programme utiliser pour interpréter le script. Tout de suite après le *shebang* se trouve le chemin d'accès du programme à utiliser (exemple : `#!/bin/sh`).

~~~php
#!/usr/bin/php
<?php
//Votre code php...
~~~

> Pour connaître le *path* de la vm PHP `whereis php`

**Rendre** le script executable :

~~~bash
chmod +x bin/generate-fakedata
~~~

Usage :

~~~bash
./bin/generate-fakedata
./bin/generate-fakedata 100
~~~

On souhaiterait à présent générer des fausses données dans *plusieurs langues* (français, anglais, etc.). On souhaite externaliser cette configuration dans un fichier d'environnement `.env` à la racine du projet.

12. **Créer un fichier** `.env` à la racine du projet pour y définir la langue par défaut (la locale). **Créer** également un fichier `.env.dist` qui servira de modèle pour les autres développeur·euses. Dans le fichier .env, ajouter la variable suivante :

~~~ini
FAKER_LOCALE="fr_FR"
~~~

> Attention : ne jamais commit le fichier `.env` ! Il peut contenir des secrets et informations sensibles (credentials) ! On crée donc un fichier `.env.dist` qui sert de modèle, avec des données d'exemple, que l'on peut commit pour *documenter* le fichier d'environnement à créer et les configurations possibles.

13. Pour charger le contenu du fichier `.env`, [**installer** le paquet vlucas/phpdotenv](https://packagist.org/packages/vlucas/phpdotenv) :

~~~bash
composer require vlucas/phpdotenv
~~~

14. **Modifier** le fichier `public/index.php` de votre service web et de vitre script `bin/generate-fakedata` pour charger les variables d'environnement au tout début du script :

~~~php
// Charger les variables d'environnement depuis le répertoire racine
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
~~~

15. **Adapter** la classe `Generator` pour qu'elle utilise la variable d'environnement `$_ENV['FAKER_LOCALE']` lors de l'initialisation de `Faker`. Si la variable n'est pas définie, prévoir la valeur par défaut `"fr_FR"`.

16. **Tester** le bon fonctionnement en changeant la valeur dans votre `.env` [(ex: en_US, es_ES, it_IT, etc. Voir les locales disponbiles)](https://fakerphp.org/#localization) via le service web et le script CLI.

On souhaite à présent générer des fausses données **dans plusieurs langues** (français, anglais, espagnol, etc.). Pour éviter de modifier le code à chaque fois (et déclencher inutilement tout le *processus qualité*), on va *externaliser* cette configuration dans un fichier d'environnement `.env` à la racine du projet.

### Qualité

On aimerait s'assurer de la *qualité des changements apportés dans le code* avant de le mettre en production ou de l'intégrer officiellement à la codebase (dans une *pipeline* d'intégration continue (CI) par exemple). Pour cela on va utiliser :

- Un *formatter* qui va appliquer des règles (coding styles)
- Un *analyseur* de code statique qui va nous aider à détecter des bugs ou des problèmes *avant* l'exécution. Le problème des langages interprétés comme PHP c'est que nous n'avons pas de compilateur pour lever de nombreuses erreurs, les problèmes arrivent à l'exécution (quand il est trop tard !)

1. **Installer** le formateur [PHPCodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer/) via *Composer*. Cet outil est composé de deux scripts :

- `phpcs` qui detecte (*s* pour *sniff*) des violations de *coding standards* (que l'on pourra choisir)
- `phpcbf` qui corrige (*bf* pour *beautify*) automatiquement les violations du standard celles qui peuvent l'être

~~~bash
composer require "squizlabs/php_codesniffer=*"
~~~

*Composer* installe localement les deux programmes dans `vendor/bin` :

2. **Testez** :

~~~bash
#Regardez notamment les options disponbiles
./vendor/bin/phpcs -h
./vendor/bin/phpcbf -h
~~~

3. **Analysez** vos sources :

~~~bash
./vendor/bin/phpcs src
./vendor/bin/phpcbf src
#En appliquant un standard, ici le standard PEAR (par défaut)
./vendor/bin/phpcs --standard=PEAR src
~~~

4. **Analysez** et **corrigez** vos sources en **utilisant** cette fois [le standard PSR-12](https://www.php-fig.org/psr/psr-12/)(déjà installé par défaut) :

~~~bash
# En mode verbose
# Faire un rapport
./vendor/bin/phpcs -v --standard=PSR12 src
# Si aucune erreur, l'execution retourne le code status (utile pour une pipeline CI !)
echo $?
# Corriger automatiquement
./vendor/bin/phpcbf -v --standard=PSR12 src
~~~

Voici quelques commandes utiles :

~~~bash
# Lister les standards installés
phpcs -i
# Ajouter un standard à la liste des paths installés
phpcs --config-set installed_paths /path/to/some-standard-cs
~~~

5. *Bonus* : **Intégrer** PHPCodeSniffer directement dans VS Code :
    1. **Installer** l'extension [PHP Sniffer & Beautifier](https://marketplace.visualstudio.com/items?itemName=ValeryanM.vscode-phpsab)
    2. **Choisir** `phpcbf` comme formateur par défaut

6. **Installer** [l'analyser statique PHPStan](https://phpstan.org/user-guide/getting-started), toujours via Composer.

~~~bash
composer require --dev phpstan/phpstan
~~~

7. **Analysez** votre code en montant *progressivement* [le niveau d'exigence](https://phpstan.org/user-guide/rule-levels) (*level*) vers des niveaux plus stricts :

~~~bash
./vendor/bin/phpstan analyze src
./vendor/bin/phpstan analyze -l3 src
./vendor/bin/phpstan analyze -l6 src
./vendor/bin/phpstan analyze -l8 src
~~~

8. Comment peut-on intégrer *phpStan* dans une pipeline CI ?

> L'écosystème PHP de pas sa longévité dispose [de très bons et nombreux outils](https://github.com/paul-schuhm/veille-php#tooling%C3%A9cosyst%C3%A8me) pour aider au développement (frameworks, lib, etc.) et à la qualité logicielle.
