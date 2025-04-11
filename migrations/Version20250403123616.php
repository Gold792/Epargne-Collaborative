<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403123616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Ajoutez uniquement la table cotisation
        $this->addSql(<<<'SQL'
            CREATE TABLE cotisation (
                id INT AUTO_INCREMENT NOT NULL,
                titre VARCHAR(255) NOT NULL,
                initiateur VARCHAR(255) NOT NULL,
                montant_objectif INT NOT NULL,
                montant_echeance INT NOT NULL,
                date_debut DATE NOT NULL,
                date_fin DATE DEFAULT NULL,
                periodicite VARCHAR(50) NOT NULL,
                membres LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)',
                description LONGTEXT DEFAULT NULL,
                rappel_auto TINYINT(1) NOT NULL,
                cotisation_publique TINYINT(1) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Supprimez uniquement la table cotisation
        $this->addSql('DROP TABLE cotisation');
    }
}
