<?php

/**
 * Couche d'abstraction sur l'origine des données.
 */
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
