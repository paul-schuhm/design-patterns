## Évaluation PHP Orienté Objet (24h)

<hr>

SPE 2 : POO CDA

Live Campus

2026

Paul Schuhmacher

Version: 1

<hr>

- [Évaluation PHP Orienté Objet (24h)](#évaluation-php-orienté-objet-24h)
- [À rendre](#à-rendre)
- [Notation](#notation)
- [Problème à résoudre (18 points)](#problème-à-résoudre-18-points)
- [Spécifications](#spécifications)
  - [Interface CLI](#interface-cli)
    - [Commandes et options](#commandes-et-options)
    - [Exemples](#exemples)
  - [Format et contenu des rapports](#format-et-contenu-des-rapports)
    - [Format pour le prélèvement à la source](#format-pour-le-prélèvement-à-la-source)
    - [Format pour le versement fiscal libératoire](#format-pour-le-versement-fiscal-libératoire)
  - [Règles métiers](#règles-métiers)
    - [SIRET](#siret)
    - [Régimes d'activité](#régimes-dactivité)
    - [Calcul des cotisations sociales](#calcul-des-cotisations-sociales)
    - [Régimes fiscaux : calcul de l'impôt sur le revenu ou du revenu imposable](#régimes-fiscaux--calcul-de-limpôt-sur-le-revenu-ou-du-revenu-imposable)
- [Calcul des indemnités de frais d'exploitation](#calcul-des-indemnités-de-frais-dexploitation)
- [Calcul final du CA TTC](#calcul-final-du-ca-ttc)
  - [Récapitulatif](#récapitulatif)
- [Jeu de données test](#jeu-de-données-test)
- [Travail préliminaire (fourni)](#travail-préliminaire-fourni)
  - [Script de départ](#script-de-départ)
  - [Schéma de la base de données](#schéma-de-la-base-de-données)
  - [Abstractions identifiées](#abstractions-identifiées)
    - [Démarrer le pattern *stratégie*](#démarrer-le-pattern-stratégie)
    - [Couche d'abstraction sur la source des données](#couche-dabstraction-sur-la-source-des-données)
- [Questions (5 points)](#questions-5-points)

## À rendre

**Publier** votre travail **sur un dépôt git public** (GitHub, Gitlab, etc.) et fournir **le lien du dépôt**. Le dépôt contiendra les sources des exercices ainsi qu'un fichier `README` donnant les instructions pour lancer le programme pour chaque exercice si besoin, ainsi que les réponses aux questions.

> Merci de vérifier que le dépôt est **public** !

Votre dépôt doit contenir *a minima* :

- Un fichier `README.md` contenant les réponses aux questions et les instructions pour lancer les programmes. Je dois avoir simplement à **copier coller les instructions** **sans me poser de questions**
- Les **sources** du projet (code PHP, fichiers `.env.dist`, fichiers de configuration composer, etc.)

> Votre dépôt doit être facilement navigable. Penser aussi à versionner **uniquement ce qui est nécessaire** et à utiliser un fichier `.gitignore`.

**Envoyer** l'URL de votre dépôt par **e-mail** à l’adresse suivante : <a href="mailto:contact@pschuhmacher.com">contact@pschuhmacher.com</a>, **ayant le sujet suivant** :

`php-x-abc`

où **`x`** est la première lettre de votre nom et **`abc`** votre prénom (j'enverrai donc un e-mail avec le sujet `php-s-paul`).

## Notation

Le **respect des consignes** et le format des documents (documents bien formés, soignés, instructions demandées fournies, sources commentées et bien formées) sera pris en compte (**3 points**). Merci de penser au correcteur.

L'évaluation est ramenée sur **20 points**.

> Durée approximative : 2h

## Problème à résoudre (18 points)

Vous devez développer un outil en ligne de commande nommé `urssafc` (un script PHP nommé `urssafc.php`) destiné aux agents de l'URSSAF pour enregistrer rapidement des auto-entrepreneurs et faire des simulations de déclarations mensuelles.

L'application doit calculer **sur un mois**, pour toute *autoentreprise* :

- le montant des impôts sur le revenu *ou* le revenu imposable
- le montant des cotisations sociales
- le CA TTC (le *Chiffre d'Affaires Toutes Taxes Comprises*, l'équivalent du *salaire net*, après paiement des cotisations sociales et prélèvement de l'impôt sur le revenu), calculé à partir du CA HT (*Chiffre d'Affaires Hors Taxe*, ce que la personne reçoit sur son compte bancaire pour une vente ou une prestation)

L'application fournira ces informations sur la sortie standard (*stdout*).

Vous devez **concevoir** et **implémenter** *une première version* de l'application **respectant les contraintes suivantes** :

- Utiliser *Composer* et l'*autoloading* (PSR-4). Le système est placé sous le namespace racine `Urssaf` (bloc `autoload` du fichier `composer.json`) (**2 points**)
- Concevoir et implémenter en **programmation orientée objet**, en faisant un bon usage des primitives du paradigme (encapsulation, polymorphisme, etc.) (**7 points**)
- Utiliser [le *Design Pattern Strategy*](https://refactoring.guru/design-patterns/strategy). **Le code client (ici notre script `urssafc.php`) ne doit pas changer** si l'on change les régimes d'activité ou les règles des régimes fiscaux (**4 points**)
- **Persister** les données (autoentreprises) dans une base de données SQLite via le module PDO (et les requêtes préparées) (**3 points**)
- Produire des valeurs métiers **correctes** (vérifier avec les données test fournies) (**2 points**)
- Le programme s'exécute **sans erreur**. (**2 points**)

## Spécifications

### Interface CLI

#### Commandes et options

L'outil propose les trois commandes suivantes :

- `add` : enregistre une nouvelle microentreprise
- `ls` : lister les microentreprises enregistrées
- `dry_declare` : simule une déclaration de revenus mensuelle pour une autoentreprise et affiche un rapport

~~~bash
php urssafc.php add "NOM_COMPLET" SIRET REGIME_ACTIVITE REGIME_FISCAL
php urssafc.php ls
php urssafc.php dry-declare ID CA_HT
~~~

`REGIME_ACTIVITE` (*activity*) :

- `bic-vente` : régime BIC(Vente)
- `bic` : régime BIC
- `bnc` : régime BNC

`REGIME_FISCAL` (*taxsystem*):

- `ps` : prélèvement à la source
- `vfl` : versement fiscal libératoire

#### Exemples

~~~bash
#Enregistrer des autoentreprises
php urssafc.php add "John Incubator Jones" 18812369758410 bic-vente ps
php urssafc.php add "Luigi Vercotti" 47623697384617 bnc vfl

#Lister
php urssafc.php ls
1 John Incubator Jones 18812369758410 bic-vente ps
2 Luigi Vercotti 47623697384617 bnc vfl
Total: 2

#Simulation d'une déclaration mensuelle
php urssafc.php dry-declare 1 3235
John Incubator Jones - BIC(Vente) | Prélèvement à la source
CA HT mensuel:           3235 EUROS
Aide spécifique:          200 EUROS
Cotisations sociales:  711,70 EUROS
Revenu imposable:      938,15 EUROS
CA TTC mensuel:       2523,30 EUROS

#Gestion des doublons
php urssafc.php add "John Incubator Jones" 18812369758410 bic-vente ps
L'autoentreprise avec le SIRET 18812369758410 existe déjà. Abandon.

#Gestion des erreurs (SIRET invalide)
php urssafc.php add "John Incubator Jones" 1234 bic-vente ps
Le SIRET n'est pas valide. Abandon.
~~~

> Bonus : Sous UNIX, en 1) introduisant un *shebang* en début de votre script (`!#/usr/bin/php`) pour indiquer au *shell* avec quel programme exécuter le script 2) rendant exécutable votre script (`chmod +x urssafc.php`) 3) l'installant sur le `PATH` (par ex `/usr/bin`) vous pouvez utiliser votre programme comme *n'importe quel commande du shell* : `urssafc ls`. PHP devient un détail d'implémentation...

### Format et contenu des rapports

Le système **calcule** les impôts et les cotisations sociales, puis produit **un rapport** ASCII contenant les informations suivantes :

- Identité : Nom complet, régie d'activité et régie fiscal
- Le CA mensuel HT
- Le montant des cotisations sociales
- Le montant de l'impôt sur le revenu **ou** le revenu imposable (en fonction du régime fiscal)
- Le CA mensuel TTC (après prélèvement des cotisations sociales et impôt sur le revenu si au régime fiscal du *Versement fiscal libératoire*)

#### Format pour le prélèvement à la source

~~~bash
John Incubator Jones - BIC(Vente) | Prélèvement à la source
CA HT mensuel:        3235,00 EUROS
Aide spécifique:      200,00 EUROS
Cotisations sociales: 711,70 EUROS
Revenu imposable:     938,15 EUROS
CA TTC mensuel:       2723,30 EUROS
~~~

#### Format pour le versement fiscal libératoire

~~~bash
Emily Brontë | BIC - Versement fiscal libératoire
CA HT mensuel:                 4050,00 EUROS
Cotisations sociales:           518,40 EUROS
Montant de l'impôt à prélever:   68,85 EUROS
CA TTC mensuel:                3462,75 EUROS
~~~

~~~bash
Ellie Williams | BNC - Versement fiscal libératoire
CA HT mensuel:                 0,00 EUROS
Cotisations sociales:          0,00 EUROS
Montant de l'impôt à prélever: 0,00 EUROS
CA TTC mensuel:                0,00 EUROS
~~~

### Règles métiers

La législation, les régimes d'activité et régimes fiscaux et les taux appliqués **sont amenés à changer** dans le futur.

#### SIRET

Le système **vérifie** que le numéro de SIRET est bien composé de 14 chiffres, sinon il rejette l'enregistrement de la microentreprise.

#### Régimes d'activité

Il existe *actuellement* **trois régimes d'activité** pour les autoentreprises :

- achat/revente de marchandises (BIC Vente)
- prestations de service commerciales ou artisanales (BIC)
- prestation de services et affiliés (non réglementés) (BNC)

#### Calcul des cotisations sociales

Les cotisations sociales sont calculées directement sur le CA HT. Voici les taux (en 2022) pour chaque *régime d'activité* :

| Régime d'Activité | Taux  |
| :---------------- | :---- |
| BIC Vente         | 22%   |
| BIC               | 12.8% |
| BNC               | 22%   |

> Par exemple, si le CA HT est de 1000 EUROS au régime BNC, je dois payer 220 EUROS de cotisations sociales.

#### Régimes fiscaux : calcul de l'impôt sur le revenu ou du revenu imposable

Il existe **deux régimes fiscaux** pour les autoentreprises (régime "microfiscal"):

- Si je suis au régime du **Prélèvement à la source**, le revenu imposable est calculé après abattement fiscal. Le règlement de l'impôt se fera plus tard auprès du centre des impôts. `Calcul du Revenu Imposable = CA HT*(1-Abbatemment forfaitaire(%))`.
- Voici les taux d'abattement forfaitaire en fonction du régime d'activité (en 2022) :

| Régime d'Activité | Taux |
| :---------------- | :--- |
| BIC Vente         | 71%  |
| BIC               | 50%  |
| BNC               | 34%  |

> Par exemple, si mon CA HT est de 100 EUROS, et que je suis au régime BNC, alors mon revenu imposable sera de 66 EUROS (abattement forfaitaire de 34%). Je paierai mon impôt plus tard, l'URSSAF ne me prélève pas.

- Si je suis au régime du **Versement fiscal libératoire**, alors l'impôt est prélevé directement sur le CA HT. Voici le taux de prélèvement en fonction du régime d'activité (en 2022) :

| Régime d'Activité | Taux |
| :---------------- | :--- |
| BIC Vente         | 1%   |
| BIC               | 1.7% |
| BNC               | 2.2% |

> Par exemple, si mon CA HT est de 1000 EUROS, et que je suis au régime BIC, alors mon impôt est calculé et prélevé directement par l'URSSAF et sera de 17 EUROS.

## Calcul des indemnités de frais d'exploitation

Depuis mars, des *indemnités de frais d'exploitation* (dispositif d'allègement financier temporaire basé sur le montant brut de l'activité) ont été mise en place  :

- Le régime *BIC Vente* a droit à une prime fixe de stockage de 200 EUROS **si le CA HT dépasse 3 000 EUROS**
- Le régime *BIC* ne peut prétendre à **aucune aide** pour l'instant
- Le régime *BNC* a droit une prime de soutien à l'activité égale à 15 % de son chiffre d'affaires **si le CA HT est inférieur à 1 500 EUROS**.

## Calcul final du CA TTC

Pour calculer le CA TTC (l'argent *gagné* par l'autoentreprise) :

- Si la microentreprise est au régime fiscal du *Prélèvement à la source*, le montant de l'impôt sera calculé par le centre des impôts plus tard. Le rapport se contente de faire apparaître le revenu imposable. :
  
  `CA TTC = CA HT - cotisations sociales + indemnités`
  
- Si la microentreprise est au régime fiscal du *Versement fiscal libératoire*, le montant de l'impôt est directement prélevé sur le CA HT par l'URSSAF. :
  
  `CA TTC = CA HT - cotisations sociales - impôt sur le revenu + indemnités`.

### Récapitulatif

| Régime d'Activité | Abattement Forfaitaire | Versement Libératoire | Cotisations Sociales |
| :---------------- | :--------------------- | :-------------------- | :------------------- |
| **BIC Vente**     | 71%                    | 1%                    | 22%                  |
| **BIC**           | 50%                    | 1.7%                  | 12.8%                |
| **BNC**           | 34%                    | 2.2%                  | 22%                  |

## Jeu de données test

| Prénom | Nom             | SIRET          | Régime d'activité | Régime fiscal                | CA HT mensuel (EUROS) |
| :----- | :-------------- | :------------- | :---------------- | :--------------------------- | :-------------------- |
| John   | Incubator Jones | 18812369758410 | BIC(Vente)        | Prélèvement à la source      | 3235                  |
| Luigi  | Vercotti        | 18823697384617 | BNC               | Versement fiscal libératoire | 2205                  |
| Emily  | Brontë          | 33512369768412 | BIC               | Versement fiscal libératoire | 4050                  |
| Ellie  | Williams        | 11112245668417 | BNC               | Versement fiscal libératoire | 0                     |

> Ellie était en vacances ce mois-ci

[Voir les résultats attendus pour John, Emily et Ellie.](#format-et-contenu-des-rapports)

## Travail préliminaire (fourni)

>Vous êtes libres de ne **pas** utiliser ce travail préparatoire (notamment l'abstraction du pattern Strategy proposée) et de concevoir et implémenter vos propres abstractions !

### Script de départ

~~~php
//Fichier: urssafc.php
<?php
declare(strict_types=1);

//Chargement de l'autoloader
require_once __DIR__ . '/vendor/autoload.php';

// 1. Configuration PDO SQLite...
// 2. Création automatique de la table si elle n'existe pas...

// Extraction des arguments passés au script
$command = $argv[1] ?? null;

switch ($command) {
    case 'add':
        $fullName = $argv[2] ?? null;
        $siret = $argv[3] ?? null;
        $activity = $argv[4] ?? null;
        $taxSystem = $argv[5] ?? null;
        
        if (!$fullName || !$siret || !$activity || !$taxSystem) {
            echo "Erreur: Arguments manquants pour 'add'.\n";
            exit(1);
        }
        // 1. Valider le SIRET, gérer les doublons, et insérer en BDD
        break;

    case 'ls':
        // 1. Lister les microentreprises
        break;

    case 'dry-declare':
        $id = isset($argv[2]) ? (int)$argv[2] : null;
        $caHt = isset($argv[3]) ? (float)$argv[3] : null;
        
        if (!$id || !$caHt) {
            echo "Erreur: Arguments manquants pour 'dry-declare'.\n";
            exit(1);
        }
        //1. Récupérer les données de l'auto-entreprise
        //2. Injecter la Strategy a un objet Contractor (autoentreprise) 
        //3. Calculer les cotisations sociales, appliquer la fiscalité et construire le rapport

        //Imprimer le rapport
        echo $contractor->buildReport($caHt);
        break;

    //Par convention, lancer une commande sans argument affiche le manuel
    default:
        echo "Usage:\n";
        echo "  php urssafc.php add \"NOM_COMPLET\" SIRET REGIME_ACTIVITE REGIME_FISCAL\n";
        echo "  php urssafc.php ls\n";
        echo "  php urssafc.php dry-declare ID CA_HT\n";
        exit(1);
}
~~~

### Schéma de la base de données

~~~SQL
CREATE TABLE IF NOT EXISTS contractor (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    full_name TEXT NOT NULL,
    siret TEXT NOT NULL UNIQUE,
    activity TEXT NOT NULL,
    tax_system TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_DATE
);
~~~

> [SQLite ne dispose PAS de type `DATE` ou `DATETIME`](https://sqlite.org/datatype3.html). On la stocke sous forme de chaîne de caractères [au format ISO8601](https://fr.wikipedia.org/wiki/ISO_8601) (`YYYY-mm-dd`, par exemple `2026-05-29`)

### Abstractions identifiées

> Ce sont des *indications*, vous êtes libres de créer vos propres abstractions !

#### Démarrer le pattern *stratégie*

~~~php
//Ici on choisit une classe abstraite pour centraliser l'implémentation de la génération du rapport, commune à tous les régimes. Ici on fait même un mélange entre le pattern *Strategy* et *Template Method*

abstract class AbstractActivityStrategy
{
    //Retourne le rapport qui sera affiché sur la sortie standard.
    public function buildReport(float $caHt, string $taxSystem): string
    {
        //À implémenter. Dépendra notamment du régime fiscal.
    }
    //Retourne le taux de cotisation social
    abstract protected function cotisationRate(): float;
    //Retourne le taux de prélèvement dans le cadre du régime fiscal "Versement fiscal libératoire" (vfl)
    abstract protected function taxDischargePayment(): float;
    //Retourne le taux d'abattement fiscal dans le cadre du régime fiscal "Prélèvement à la source" (ps)
    abstract protected function abatementRate(): float;

    //De la logique métier propre à chaque régime d'activité
    //Calcul des indemnités de frais d'exploitation
    abstract protected function specificSubsidy(float $caHt): float;
}
~~~

#### Couche d'abstraction sur la source des données

On utilise [le pattern *Repository*](https://martinfowler.com/eaaCatalog/repository.html) sur la frontière du *modèle* pour lui offrir une API d'accès aux données sous la forme d'une collection. Le modèle n'a pas besoin de savoir *où* ni *comment* sont persistées les données  :

~~~php
class ContractorRepository
{
    //Injection de dépendance dans le constructeur de l'instance PDO (accès a la base de données)
    public function __construct(private PDO $pdo) {}


    /**
     * Retourne l'identifiant généré par la base pour le nouveau record
     * @throws \Exception Si l'insertion en base de données échoue
     * @return int
     */
    public function save(string $fullName, string $siret, string $activity, string $taxSystem): int
    {
        //À implémenter...
    }

   /**
    * @return Contractor|null
    */
    public function find(int $id): ?Contractor
    {  
        //À implémenter...
    }

    /**
    * @return array<Contractor>
    */
    public function findAll(): array
    {  
        //À implémenter...
    }
}
~~~

## Questions (5 points)

**Répondez** de manière *succincte* aux questions suivantes :

> Ces questions sont avant tout là pour vous faire prendre du recul sur le design que vous avez mis en place et ses avantages

1. En quoi votre système respecte [l'*Open Close Principle*](https://en.wikipedia.org/wiki/Open%E2%80%93closed_principle) (ouvert à l'extension, fermé à la modification) ?
2. *Pourquoi* utilisons-nous un *design pattern* (*Stratégie*) ici ? **Justifier** d'après les spécifications et le contexte métier.
3. Pourquoi est-il important que votre *Domain* ou *Model* (le code métier, contenu du dossier `src`) reste indépendant ? Serait-il facilement réutilisable pour développer une version web de l'application ?
4. **Réaliser** un **diagramme de classes UML** du système. Le script CLI apparaîtra sous la classe `Client`. **Indiquer** avec un schéma de couleur les classes/interfaces participantes au pattern *Strategy*. On ne fera apparaître que les classes/interfaces participantes avec leurs noms et leurs associations (pas les méthodes, ni les attributs). Pour cela, **utiliser votre logiciel favori** ([Diagrams(web)](https://app.diagrams.net/), [Umlet](https://www.umlet.com/), à la main, etc.). **Publier** le diagramme sur le dépôt.
