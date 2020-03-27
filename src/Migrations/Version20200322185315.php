<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200322185315 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE option_for_help CHANGE groceries groceries TINYINT(1) DEFAULT NULL, CHANGE garbage garbage TINYINT(1) DEFAULT NULL, CHANGE walking_dog walking_dog TINYINT(1) DEFAULT NULL, CHANGE dry_cleaning dry_cleaning TINYINT(1) DEFAULT NULL, CHANGE deliver_take_away deliver_take_away TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE option_for_help CHANGE groceries groceries TINYINT(1) NOT NULL, CHANGE garbage garbage TINYINT(1) NOT NULL, CHANGE walking_dog walking_dog TINYINT(1) NOT NULL, CHANGE dry_cleaning dry_cleaning TINYINT(1) NOT NULL, CHANGE deliver_take_away deliver_take_away TINYINT(1) NOT NULL');
    }
}
