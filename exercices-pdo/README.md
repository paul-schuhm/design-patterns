# Partie 4/4 Problèmes de persistence avec l'API PDO

## Problème 1 : Utiliser une base de données SQLite pour un site web

L'objectif est de créer un site web qui se connecte à [une base de
données SQLite locale](https://sqlite.org/), récupère des *articles* de
blog, et les affiche sur la page d'accueil *du plus récent au plus
ancien*.

## Partie 1 : Le site web (accès en lecture)

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

## Partie 2 : Publier des articles (écriture avec requêtes préparées)

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

|                              \| \|\<-- 3. "Inscription OK !" -----\|
  (Instantané) \| \| \| (L'utilisateur est libéré) \| \| : \| \| :
  (Pendant que l'utilisateur navigue déjà ailleurs...) \| \| : \|\<-- 4.
  "Y a-t-il un job ?" ---\| (Boucle infinie) : \|--- 5. "Oui, le voici"
  ------\>\| : \| \|--- 6. Envoie l'email (5s) : \|\<-- 7. Supprime le
  Job -------\|


    Pourquoi dit-on que c'est asynchrone ?

    Parce que le cycle de vie de la requête de l'utilisateur (étapes 1 à 3) est complètement décorrélé du traitement lourd de l'email (étapes 4 à 7). L'utilisateur n'attend pas l'email. C'est de l'asynchronisme par découplage de processus.

    ## Message Queueing system

    Les 3 rôles de l'architecture (Le pattern Producteur/Consommateur)

    Dans le monde des architectures distribuées et du découplage lâche, on utilise des rôles très précis :

        Le Producteur (Producer) : C'est votre Script Web. C'est lui qui crée le message (le Job) et l'envoie.

        Le Consommateur (Consumer / Worker) : C'est votre Worker Daemon. C'est lui qui attend les messages et exécute le vrai travail (l'envoi de l'email).

        Le Courtier (Message Broker) : C'est le tiers de confiance au milieu. Son seul rôle est de recevoir, stocker et distribuer les messages. Dans votre exemple actuel, c'est la Base de Données (avec votre classe DatabaseQueue) qui fait office de Message Broker.

    Pourquoi utilise-t-on le mot "Broker" ?

    Le mot Broker signifie "courtier" ou "intermédiaire".

    Si vous dites à vos étudiants d'imaginer une agence immobilière :

        L'acheteur (le Worker) ne va pas frapper à toutes les portes de la ville.

        Le vendeur (le Script Web) ne va pas courir les rues pour trouver un acheteur.

        Ils passent tous les deux par un courtier (le Broker) qui centralise les offres.

    Dans votre code, le script web et le worker ne se connaissent pas. Ils connaissent uniquement la Base de Données. La DB est donc bien le Broker.

    La transition parfaite pour votre cours : SQL vs Vrais Brokers

    C'est le moment idéal pour expliquer à vos étudiants la différence entre "bricoler" un broker avec MySQL et utiliser un outil dédié.

    Dans un projet professionnel, on remplace la DB par un vrai outil de Message Broker (comme RabbitMQ ou Redis).

    En résumé pour les étudiants

    L'ensemble [Script Web + DB + Worker] est une architecture de file d'attente.

        Le script web produit.

        Le worker consomme.

        La base de données de l'exercice joue le rôle (temporaire) de Message Broker pour stocker les données entre les deux.

    Est-ce que cette distinction de vocabulaire vous paraît claire pour la présenter à votre classe ?

    ## Approche sans poll

    Pour passer à un "vrai" broker sans faire de polling (le fameux SELECT ou sleep(1) en boucle qui fatigue les serveurs), il faut changer de paradigme et passer en mode Push. Avec ce modèle, c'est le broker qui va "réveiller" le worker dès qu'un message arrive.

    En PHP, l'outil le plus accessible, ultra-rapide et massivement utilisé en production pour jouer ce rôle est Redis.

    Redis possède des fonctions natives de listes bloquantes (BLPOP). Quand le worker demande un job, si la liste est vide, Redis met la connexion PHP "en attente" au niveau du réseau. Dès qu'un script web ajoute un job, Redis pousse instantanément le job vers le worker. Zéro requêtes inutiles, CPU à 0%.

    Voici comment transformer l'exercice de vos étudiants avec Redis.

    composer require predis/predis

```{=html}
<?php
// index.php (Script Web)
require 'vendor/autoload.php';
require_once 'EmailJob.php';

$redis = new Predis\Client([
    'scheme' =>
```
'tcp', 'host' =\> '127.0.0.1', 'port' =\> 6379, \]);

// Simulation d'une inscription \$job = new
EmailJob("charlie@example.com", "Bienvenue Charlie !");

// On sérialise l'objet et on l'envoie dans la liste Redis
$redis->lpush('queue:emails', serialize($job));

echo "Inscription enregistrée de manière asynchrone !`\n`{=tex}"; \~\~\~

2.  Le Consommateur : Le Worker sans Poll (worker.php)

C'est ici que la magie du vrai broker opère. On utilise la commande
BLPOP (Blocking Left Pop).

Le premier argument est le nom de la liste, le deuxième est le timeout
(en secondes). Si on met 0, le worker attendra indéfiniment sans
consommer de CPU, jusqu'à ce qu'un message arrive.

``` php
<?php
// worker.php (Lancé en CLI)
require 'vendor/autoload.php';
require_once 'JobInterface.php';
require_once 'EmailJob.php';

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
    'read_write_timeout' => 0 // Impératif pour ne pas couper la connexion d'attente
]);

echo "=== WORKER LIVE (SANS POLL) DÉMARRÉ ===\n";
echo "En attente de messages via Redis...\n\n";

while (true) {
    // BLPOP bloque l'exécution du script PHP tant que la liste est vide.
    // Pas de sleep(), pas de boucle folle. PHP dort au niveau réseau.
    $result = $redis->blpop('queue:emails', 0);

    // $result[0] contient le nom de la clé ('queue:emails')
    // $result[1] contient la valeur (le job sérialisé)
    $payload = $result[1];

    echo "[Broker] Message reçu instantanément !\n";

    /** @var JobInterface $job */
    $job = unserialize($payload);
    $job->execute();

    echo "---------------------------------\n";
}
```

Avantages :

-   Économie de ressources : Si vous lancez le gestionnaire de tâches
    (comme top ou htop sur Linux), vous verrez que le script worker.php
    consomme 0% de CPU et 0% d'I/O disque tant qu'il n'y a pas
    d'inscription.
-   Instantanéité : Dès que vous exécutez index.php, le worker s'anime à
    la milliseconde près. Il n'y a plus le délai de 1 seconde provoqué
    par le sleep(1) de l'ancienne boucle.
-   Robustesse et Scaling (Le bonus POO) : Expliquez-leur que si le site
    web grossit, il suffit de lancer plusieurs process php worker.php.
    Redis va distribuer les messages un par un à chaque worker
    automatiquement, sans qu'aucun job ne soit traité deux fois. C'est
    du *Load Balancing*.

Gérer proprement le worker :

Dans l'écosystème PHP traditionnel (notamment si on utilise Laravel
Queues ou Symfony Messenger), l'outil roi s'appelle Supervisor.

Supervisor est un utilitaire système (un démon Linux) dont le seul
travail est de surveiller d'autres processus. On lui donne un fichier de
configuration simple, et il s'occupe de tout. Ce que fait Supervisor en
pratique :

    Démarrage automatique : Il lance les workers dès que le serveur démarre.

    Multi-processing (Le Scaling) : On lui dit dans sa configuration : "Je veux que tu maintiennes en permanence 4 instances de worker.php en ligne". Il va ouvrir les 4 processus tout seul.

    Auto-healing : Si un worker plante (à cause d'une erreur PHP fatale, d'une coupure de base de données ou d'un dépassement de mémoire), Supervisor le détecte instantanément et le relance.

À quoi ressemble la configuration pour vos étudiants :

Voici un exemple de fichier /etc/supervisor/conf.d/php-worker.conf :
Ini, TOML

\[program:php-worker\] process_name=%(program_name)s\_%(process_num)02d
command=php /var/www/html/worker.php autostart=true autorestart=true
user=www-data numprocs=4 ; \<--- C'est ici qu'on définit le nombre de
workers en parallèle ! redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/worker.log

--\>
