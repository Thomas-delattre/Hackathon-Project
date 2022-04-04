<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404100701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moto ADD media_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE moto ADD CONSTRAINT FK_3DDDBCE4EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3DDDBCE4EA9FDD75 ON moto (media_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moto DROP FOREIGN KEY FK_3DDDBCE4EA9FDD75');
        $this->addSql('DROP INDEX UNIQ_3DDDBCE4EA9FDD75 ON moto');
        $this->addSql('ALTER TABLE moto DROP media_id');
    }
}
