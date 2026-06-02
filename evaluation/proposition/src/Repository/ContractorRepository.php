<?php

declare(strict_types=1);

namespace Urssaf\Repository;

use PDO;
use Exception;
use Urssaf\Model\Contractor;
use Urssaf\Model\BICActivityStrategy;
use Urssaf\Model\BICVenteActivityStrategy;
use Urssaf\Model\BNCActivityStrategy;

class ContractorRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * Persiste une auto-entreprise si elle ne l'a pas déjà été. Le SIRET sert d'identifiant métier unique.
     *
     * @param string $fullName
     * @param string $siret
     * @param string $activity
     * @param string $taxSystem
     * @return void
     */
    public function save(string $fullName, string $siret, string $activity, string $taxSystem): void
    {
        // Gestion des doublons
        $stmtCheck = $this->pdo->prepare("SELECT id FROM contractor WHERE siret = :siret");
        $stmtCheck->execute([':siret' => $siret]);
        if ($stmtCheck->fetch()) {
            throw new Exception("L'autoentreprise avec le SIRET {$siret} existe déjà. Abandon.");
        }

        //Validation du SIRET...

        // Utilisation d'une requête préparée.
        $sql = "INSERT INTO contractor (full_name, siret, activity, tax_system) VALUES (:name, :siret, :activity, :tax)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $fullName,
            ':siret' => $siret,
            ':activity' => $activity,
            ':tax' => $taxSystem
        ]);
    }

    //Retrouve et instancie les objets Contractor en passant les stratégies adéquates au runtime.
    //Les conditionnelles/'switch' (match ici) sont centralisés ici et sous contrôle !
    //On pourrait rajouter un DP constructor ici pour bien isoler l'instanciation et la construction de ces objets dans un module dédié.
    public function find(int $id): ?Contractor
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contractor WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;
        return $this->mapRowToContractor($row);
    }

    /**
     * Récupère TOUS les auto-entrepreneurs de la base de données
     * @return Contractor[]
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM contractor ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $contractors = [];
        foreach ($rows as $row) {
            $contractors[] = $this->mapRowToContractor($row);
        }

        return $contractors;
    }

    /**
     * Centralise l'instanciation de Contractor et le choix de la Strategy (Pattern Factory)
     */
    private function mapRowToContractor(array $row): Contractor
    {
        // Résolution de la stratégie selon l'activité stockée en BDD
        $strategy = match ($row['activity']) {
            'bnc' => new BNCActivityStrategy(),
            'bic' => new BICActivityStrategy(),
            'bic-vente' => new BICVenteActivityStrategy(),
            default => throw new Exception("Régime d'activité inconnu : {$row['activity']}"),
        };

        // Construction de l'objet avec toutes ses dépendances
        return new Contractor(
            (int)$row['id'],
            $row['full_name'],
            $row['tax_system'],
            $row['siret'],
            $strategy
        );
    }
}
