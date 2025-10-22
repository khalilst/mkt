<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251018195525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
            CREATE TABLE measurement (
                id INT AUTO_INCREMENT NOT NULL,
                measurement_set_id INT NOT NULL,
                measured_at DATETIME NOT NULL,
                temperature DOUBLE PRECISION NOT NULL,
                INDEX IDX_MEASUREMENT_SET_ID (measurement_set_id),
                INDEX IDX_MEASUREMENT_SET_ID_MEASURED_AT (measurement_set_id, measured_at),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql(<<<SQL
            CREATE TABLE measurement_set (
                id INT AUTO_INCREMENT NOT NULL,
                title VARCHAR(255) NOT NULL,
                mkt DOUBLE PRECISION DEFAULT NULL,
                created_at DATETIME NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        $this->addSql(<<<SQL
            ALTER TABLE measurement
                ADD CONSTRAINT FK_MEASUREMENT_SET
                FOREIGN KEY (measurement_set_id)
                REFERENCES measurement_set (id)
                ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_MEASUREMENT_SET');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('DROP TABLE measurement_set');
    }
}
