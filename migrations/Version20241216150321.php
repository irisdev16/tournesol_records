<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216150321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE track_style (track_id INT NOT NULL, style_id INT NOT NULL, INDEX IDX_434A1ACD5ED23C43 (track_id), INDEX IDX_434A1ACDBACD6074 (style_id), PRIMARY KEY(track_id, style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE track_style ADD CONSTRAINT FK_434A1ACD5ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE track_style ADD CONSTRAINT FK_434A1ACDBACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track_style DROP FOREIGN KEY FK_434A1ACD5ED23C43');
        $this->addSql('ALTER TABLE track_style DROP FOREIGN KEY FK_434A1ACDBACD6074');
        $this->addSql('DROP TABLE track_style');
    }
}
