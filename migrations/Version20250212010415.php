<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212010415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, specification_id INT NOT NULL, modified_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8CDE5729908E2FFE (specification_id), INDEX IDX_8CDE572999049ECE (modified_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE5729908E2FFE FOREIGN KEY (specification_id) REFERENCES specification (id)');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE572999049ECE FOREIGN KEY (modified_by_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74D1EB0A74D1EB0A ON activity_code (activity_code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E3F1A9A5E237E06 ON specification (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE5729908E2FFE');
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE572999049ECE');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP INDEX UNIQ_74D1EB0A74D1EB0A ON activity_code');
        $this->addSql('DROP INDEX UNIQ_E3F1A9A5E237E06 ON specification');
    }
}
