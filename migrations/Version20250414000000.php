<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour créer la table cotisation
 */
final class Version20250414000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table cotisation avec tous les attributs requis';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cotisation (
            id INT AUTO_INCREMENT NOT NULL,
            titre VARCHAR(255) NOT NULL,
            type_cotisation VARCHAR(20) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            montant_objectif DOUBLE PRECISION NOT NULL,
            montant_minimum DOUBLE PRECISION NOT NULL,
            montant_par_echeance DOUBLE PRECISION DEFAULT NULL,
            date_debut DATETIME NOT NULL,
            date_fin DATETIME DEFAULT NULL,
            periodicite VARCHAR(20) DEFAULT NULL,
            frequence_periode INT DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE cotisation');
    }
}