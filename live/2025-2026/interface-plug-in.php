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

//Fournir UNE implémentation possible de ma source de données (repository)
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

//Enregistrer "quelque-part" ce user (base de données MySQL, NoSQL, dans un simple fichier texte, fichier JSON, soumettre requête vers un service API, etc.). Mon code client se moque bien des détails (où). Il veut s'assurer que les données soient enregistrées, c'est tout !

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
