<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251208103201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E79F37AE5 FOREIGN KEY (id_user_id) REFERENCES "user" (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E79F37AE5 ON item (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E79F37AE5');
        $this->addSql('DROP INDEX IDX_1F1B251E79F37AE5');
        $this->addSql('ALTER TABLE item DROP id_user_id');
    }
}
