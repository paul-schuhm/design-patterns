<?php

class User
{
    public function __construct(
        public string $fullName,
        public Repository $repository
    ) {}

    //Persister les données (état de l'utilisateur)
    public function save()
    {
        $this->repository->save($this);
    }
}

//Module de persistance (écrire/lire) : son API
//Abstraction sur l'origine/emplacement des données
interface Repository
{
    public function save(User $user): bool;
}

//Fournir UNE implémentation possible de ma source de données (repository)
class StdoutRepository implements Repository
{
    #[Override]
    public function save(User $user): bool
    {
        echo "Enregistre sur la sortie standard..." . PHP_EOL;
        //Implémentation...
        return true;
    }
}

//Fournir UNE AUTRE implémentation possible de ma source de données (repository)
class APIRepository implements Repository
{
    #[Override]
    public function save(User $user): bool
    {
        echo "Envoie une requête POST pour persister auprès d'une web API..." . PHP_EOL;
        //Implémentation...
        return true;
    }
}

//Enregistrer "quelque-part" ce user (base de données MySQL, NoSQL, dans un simple fichier texte, fichier JSON, soumettre requête vers un service API, etc.). 
//Mon code client se moque bien des détails (où, comment, quelle technologie). 
//Il veut s'assurer que les données soient enregistrées, c'est tout !

//Code client :

$modePersistance = $argv[1] ?? 'undefined';

//Gestion d'erreur des paramètres fournis au script
if ($modePersistance === 'undefined') {
    echo "Usage : php index.php MODE" . PHP_EOL;
    echo "MODE : stdout or api" . PHP_EOL;
    exit;
}

switch ($modePersistance) {
    case 'stdout':
        $repo = new StdoutRepository();
        break;
    case 'api':
        $repo = new APIRepository();
        break;
    default:
        throw new Exception("Mode de persistance inconnu");
}

//Configuration : injecte une implémentation du Repository à la création
//Injection de dépendances via le constructeur
$user = new User("Jane Doe", $repo);

//Architecture 'Plug-in' (modulaire) : je peux changer d'implémentation sans changer le code client ! Car l'interface est respectée (contrat)
$user->save();


/*
Discussion :

- Ici on est dans une approche objet 'Service' (A. Kay). User a une API : save(): bool. 
On pourrait aussi choisir de l'externaliser complètement (en retirant cette méthode de User) en la déléguant au repository uniquement :

~~~
$repo->save($user); 
~~~

- On a une dépendance "cyclique" :
    - User dépend de l'interface Repository
    - Repository dépend de User (pour le persister)

On peut briser cette dépendance en donnant à User une méthode toValueObject(): UserData ou toDTO(): UserData, où UserData est un value objet (readonly) qui se contente de transporter les données (pratique courante dans l'architecture hexagonale, où les modules communiquent via des "messages" standardisés)

Cela donnerait :

~~~
//Value objet pour transporter les données, émise par la classe User
readonly class UserData
{
    public function __construct(
        public string $fullName
    ) {}
}

class User
{
    public function __construct(
        private string $fullName,
        private Repository $repository
    ) {}

    //Fournit sa représentation sous forme de value object
    private function toDTO(): UserData
    {
        return new UserData($this->fullName);
    }

    public function save(): void
    {
        $dto = $this->toDTO();
        //Repository ne dépend que cette représentation
        $this->repository->save($dto);
    }
}

interface Repository
{
    public function save(UserData $data): bool;
}

class APIRepository implements Repository
{
    public function save(UserData $data): bool
    {
    }
}
~~~

Le flot de dépendances entre modules devient ainsi :

                            -------------------------------------------    
                            |                                         |
                            v                                         |
[APIRepository] ----> [Repository ] ----> [ UserData (DTO) ] <---- [ User ]

*/


