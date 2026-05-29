# Partie 4/4 Problèmes de persistence avec l'API PDO

-   [Partie 4/4 Problèmes de persistence avec l'API
    PDO](#partie-44-problèmes-de-persistence-avec-lapi-pdo)
    -   [Problème 1 : Utiliser une base de données SQLite pour un site
        web](#problème-1--utiliser-une-base-de-données-sqlite-pour-un-site-web)
        -   [Partie 1 : Le site web (accès en
            lecture)](#partie-1--le-site-web-accès-en-lecture)
        -   [Partie 2 : Publier des articles (écriture avec requêtes
            préparées)](#partie-2--publier-des-articles-écriture-avec-requêtes-préparées)
    -   [*Bonus* : Externalisation de la
        configuration](#bonus--externalisation-de-la-configuration)

## Problème 1 : Utiliser une base de données SQLite pour un site web

L'objectif est de créer un site web qui se connecte à [une base de
données SQLite locale](https://sqlite.org/), récupère des *articles* de
blog, et les affiche sur la page d'accueil *du plus récent au plus
ancien*.

### Partie 1 : Le site web (accès en lecture)

1.  **Vérifier** que l'extension `pdo_sqlite` [SQLite PDO Driver
    (PDO_SQLITE)](https://www.php.net/manual/en/ref.pdo-sqlite.php) est
    bien installée :

``` bash
#Lister les modules installés et activés de la VM
php -m
php -m | grep pdo_sqlite
pdo_sqlite
```

Sinon, [installez-la](https://www.php.net/manual/en/ref.pdo-sqlite.php).
Sous Debian/Ubuntu, avec PHP 8.5 :

``` bash
sudo apt install php8.5-sqlite3
```

2.  **Initialiser** un nouveau projet avec Composer.
3.  **Créer** un dossier `public` avec la structure suivante :

``` bash
├── composer.json
├── public
│   ├── assets
│   │   └── style.css
│   └── index.php
├── src
└── vendor
└── blog.db <= la base de données SQLite
```

4.  **Installer** le package
    [symfony/var-dumper](https://symfony.com/doc/current/components/var_dumper.html),
    fournissant les fonctions `dump()` et `dd()` (*dump-die*) *très
    utiles* pour le debug dans le contexte web !

5.  Pour la base de données, **créer** une classe `Database` dans
    `src/Database.php`. Voici des indications sur son implémentation
    (**libre à vous** de les adapter ou de ne pas suivre ces indictions
    pour *designer* vous-même cette classe !) :

    1.  Le constructeur doit **initialiser la connexion PDO** à un
        fichier SQLite `blog.db`. Pensez à **activer le mode d'erreur de
        PDO** (`ERRMODE_EXCEPTION`).
    2.  Une méthode `initialize(): bool` qui a pour fonction de :
        1.  **Créer** la table `posts` **si elle n'existe pas**. Sinon,
            elle ne fait rien. La table `posts` doit contenir les
            attributs suivant :
            1.  `id` (clé primaire auto-incrémentée),
            2.  `title`,
            3.  `content`
            4.  `created_at`, date de création de l'article
            5.  `published_at`, date de publication de l'article. Si
                l'article n'est pas publié, vaut `null`
        2.  **Créer un jeu de données test** (3 ou 4 articles publiés à
            différentes dates)
    3.  Une méthode `latestPosts(): Post[]` qui récupère tous les
        articles publiés du plus récent au plus ancien. La méthode
        récupère et retourne les données sous forme de tableau (dans un
        premier temps)
    4.  *Bonus* : utiliser [le pattern
        *Singleton*](https://refactoring.guru/fr/design-patterns/singleton/php/example)
        pour assurer qu'une seule instance de base de données est crée
        (une seule connexion) lors de l'execution du site web.

> Sur SQLite, un fichier = une base de données.

6.  Dans le fichier `public/index.php` (site web), **créer** la page
    d'accueil (markup HTML) du site :
    1.  Inclure l'*autoloader* généré par Composer
    2.  Instancier l'objet `Database` :
        1.  **Appeler** la méthode pour initialiser la base de données
            (ainsi, au premier chargement de la page, la base se crée et
            se remplit toute seule)
        2.  **Appeler** la méthode pour récupérer les posts publiés.
    3.  **Afficher** les articles en pensant à bien **échapper toutes
        les données issues de la base**, avec [la fonction native
        `htmlentities()`](https://www.php.net/htmlentities)

Contraintes et propriétés du système :

-   N'utiliser aucun `require` (hormis celui de l'*autoload*)
-   Ne pas crasher si la base de données `blog.db` n'existe pas encore
-   Le tri est fait **en SQL** (via la requête) et **non en PHP**
-   L'affichage est protégé contre les injections de scripts (attaque
    XSS), grâce à **l'échappement correct des données issues de la
    base**.

Pour tester et développer votre site web, **servez-le** en local (par
exemple sur le port libre `8080`) avec le serveur *built-in* de PHP :

``` bash
php -S localhost:8080 -t public
```

### Partie 2 : Publier des articles (écriture avec requêtes préparées)

1.  **Modifier** la classe `Database.php` pour **ajouter une nouvelle
    méthode** capable de recevoir les données du formulaire et de les
    écrire dans la base de données SQLite. Cette méthode **doit réaliser
    une requête préparée** pour se prémunir des injections SQL !
2.  Sur la page du site web, **ajouter** un formulaire pour publier un
    nouvel article :
    1.  Il doit pointer `publish_post.php` et utiliser la méthode `POST`
    2.  Il lui faut *trois* champs :
        1.  un `<input>` pour le titre
        2.  un `<textarea>` pour le contenu
        3.  un input de type `submit` pour la soumission
3.  **Créer** le script `public/publish_post.php`. Il aura la charge de
    **traiter** le formulaire (**validation**) et d'**enregistrer** le
    nouveau post en base. Une fois l'insertion réussie en base, le
    script effectue une redirection HTTP ([pattern
    Post-Redirect-Get](https://fr.wikipedia.org/wiki/Post-redirect-get))
    vers `/` pour éviter que l'article ne soit renvoyé en double
    (re-soumission du formulaire) si l'utilisateur·ice rafraîchit la
    page et pour afficher la page d'accueil.

Contraintes et propriétés du système :

-   La requête d'insertion utilise obligatoirement les requêtes
    préparées (`$pdo->prepare()` et `$stmt->execute()`)
-   Le site ne plante pas et **n'insère pas de ligne vide** si on clique
    sur *"Envoyer"* avec des champs vides
-   Une fois le formulaire soumis et la redirection vers la *home*
    effectuée, **le nouvel article apparaît immédiatement tout en haut
    de la liste**

## *Bonus* : Externalisation de la configuration

On souhaite que le chemin du fichier de base de données n'apparaissent
plus *en dur* dans la classe `Database` mais qu'elle soit *injectée à
l'exécution via une variable d'environnement*. Nous allons donc utiliser
des variables d'environnement pour configurer le site web.

1.  À la racine du projet, **créer** un fichier `.env`
2.  **Créer** une variable `PATH_DATABASE`, qui aura pour valeur le
    chemin vers le fichier SQLite (relatif à la racine du projet)
3.  **Ajouter** le code nécessaire pour utiliser la variable
    `PATH_DATABASE` dans l'application. Elle sera disponible dans la
    *superglobale* `$_ENV` de PHP. Pour cela, vous pouvez installer et
    utiliser le paquet [**paquet
    symfony/dotenv**](https://packagist.org/packages/symfony/dotenv)
    *via* Composer.

Si le fichier `.env` est manquant ou si la variable d'environnement
`DB_DATABASE` est vide, l'application doit lever une exception et
*échouer avec grâce*, en répondant avec un code status HTTP 500
(*Internal Error*) ou avec une page web de fallback disant de revenir
plus tard.
