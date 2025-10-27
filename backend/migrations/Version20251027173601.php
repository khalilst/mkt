<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027173601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement_set ADD `status` SMALLINT NOT NULL DEFAULT 0 AFTER `mkt`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement_set DROP `status`');
    }
}
