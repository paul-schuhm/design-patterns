# Pratique : Mini site web pour pratique les fondamentaux de PHP, sans *autoloading*

- [Pratique : Mini site web pour pratique les fondamentaux de PHP, sans *autoloading*](#pratique--mini-site-web-pour-pratique-les-fondamentaux-de-php-sans-autoloading)
  - [Description](#description)
  - [Lancer la démo](#lancer-la-démo)
  - [Tester](#tester)
  - [Remarques sur le système actuel](#remarques-sur-le-système-actuel)
  - [Vers l'*autoloading*](#vers-lautoloading)
  - [Références utiles](#références-utiles)


## Description

Un petit projet de site web affichant une liste de publications et des informations sur leurs auteurs. Cette démo n'utilise pas *encore* l'autoloading PSR-4 (pour raison pédagogique).

Remarquer les difficultés concernant la gestion des dépendances entre scripts. Essayer de renommer le dossier `Models` en `models` par exemple ou de déplacer

## Lancer la démo

~~~bash
php -S localhost:5001 -t demo_autoloading
~~~

## Tester

~~~bash
curl localhost:5001
~~~

## Remarques sur le système actuel

- **Redondance** : On doit écrire une ligne `require_once` pour chaque classe utilisée ;
- **Confusion** sur les *path* : Un chemin relatif est interprété **à partir de l'emplacement du fichier qui exécute le script principal** (`index.php` par exemple ici), et non à partir de l'emplacement du fichier où se trouve l'instruction `require_once`.
- Tout **refactoring produit des erreurs** de paths :
  - Si je déplace `User.php`, son `require_once` relatif dans son propre fichier est cassé ;
  - Si j'oublie d'inclure la bonne classe dans `index.php`, l'application plante avec l'erreur : `Fatal error: Uncaught Error: Class '...' not found`.
- Ordre d'Inclusion : il faut inclure les classes dans le bon ordre hiérarchique pour éviter les erreurs.
- **Nécessité d'un chemin absolu Unique** `define('ROOT_PATH', __DIR__ . '/');` dans `config.php`. Problème, à présent **un modèle comme User doit inclure `config.php`** pour être utilisable.


Au final, on passe **plus de temps à gérer les chemins et les require_once qu'à écrire la logique métier**

## Vers l'*autoloading*

Avec la stratégie d'*autoloading*, **tout ce code manuel** et ces **problématiques** (Où je suis ? Quel chemin je dois utiliser dans mon inclusion ? Etc.) **disparaissent**. 

Le système va savoir **automatiquement** où **trouver** le fichier User.php, et nous n'aurons plus à faire de `require_once` manuel.

C'est le but de la démo suivante.

## Références utiles

- PSR-1
- PSR-12