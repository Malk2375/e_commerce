<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513160236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piece ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B2312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_44CA0B2312469DE2 ON piece (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE piece DROP FOREIGN KEY FK_44CA0B2312469DE2');
        $this->addSql('DROP INDEX IDX_44CA0B2312469DE2 ON piece');
        $this->addSql('ALTER TABLE piece DROP category_id');
    }
}
