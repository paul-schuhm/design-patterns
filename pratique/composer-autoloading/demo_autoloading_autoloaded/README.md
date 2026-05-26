# Pratique : Mini site web pour pratique les fondamentaux de PHP, AVEC AUTOLOADING

- [Pratique : Mini site web pour pratique les fondamentaux de PHP, AVEC AUTOLOADING](#pratique--mini-site-web-pour-pratique-les-fondamentaux-de-php-avec-autoloading)
  - [Description](#description)
  - [Lancer la démo](#lancer-la-démo)
  - [Tooling](#tooling)
    - [Linter](#linter)
    - [Analyse statique](#analyse-statique)
    - [Tests unitaires](#tests-unitaires)
    - [Génération de la doc de référence](#génération-de-la-doc-de-référence)
  - [Remarques sur le système actuel](#remarques-sur-le-système-actuel)
  - [Références utiles](#références-utiles)


## Description

Un petit projet de site web affichant une liste de publications et des informations sur leurs auteurs. Cette démo utilise :

- **composer et l'autoloading PSR-4**;
- le **linter** phpcs/phpcbf avec les codings standard PSR12;
- l'**analyseur statique** phpStan;
- des tests unitaires avec PHPUnit;
- les Docblock et PHP Documentor pour générer sa doc de référence.

## Lancer la démo

~~~bash
composer install
php -S localhost:5001 -t demo_autoloading
~~~

~~~bash
curl localhost:5001
~~~

## Tooling

### Linter

~~~bash
./vendor/bin/phpcs src
~~~

### Analyse statique

~~~bash
./vendor/bin/phpstan analyze -l6 src
~~~

### Tests unitaires

~~~bash
./vendor/bin/phpunit --testdox tests/
~~~

### Génération de la doc de référence

~~~bash
docker run --rm -v "$(pwd):/data" phpdoc/phpdoc:3 -d src -t docs
~~~

## Remarques sur le système actuel

<!-- 
A compléter
 -->

## Références utiles

- [Composer : Basic Usage](https://getcomposer.org/doc/01-basic-usage.md), documentation officielle de Composer
- [PSR-4: Autoloader](https://www.php-fig.org/psr/psr-4/), publié par PHP-FIG
- [The VarDumper Component](https://packagist.org/packages/symfony/var-dumper), composant développé par Symfony