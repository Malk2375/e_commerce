<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510135432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1714819A0');
        $this->addSql('DROP INDEX IDX_64C19C1714819A0 ON category');
        $this->addSql('ALTER TABLE category CHANGE type_id_id type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1C54C8C93 FOREIGN KEY (type_id) REFERENCES type_category (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1C54C8C93 ON category (type_id)');
        $this->addSql('ALTER TABLE part DROP FOREIGN KEY FK_490F70C66DF71F3C');
        $this->addSql('DROP INDEX IDX_490F70C66DF71F3C ON part');
        $this->addSql('ALTER TABLE part CHANGE piece_id_id piece_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part ADD CONSTRAINT FK_490F70C6C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id)');
        $this->addSql('CREATE INDEX IDX_490F70C6C40FCFA8 ON part (piece_id)');
        $this->addSql('ALTER TABLE piece DROP FOREIGN KEY FK_44CA0B23F78A56EE');
        $this->addSql('DROP INDEX IDX_44CA0B23F78A56EE ON piece');
        $this->addSql('ALTER TABLE piece CHANGE subcategory_id_id subcategory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B235DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES sub_category (id)');
        $this->addSql('CREATE INDEX IDX_44CA0B235DC6FE57 ON piece (subcategory_id)');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F7989777D11E');
        $this->addSql('DROP INDEX IDX_BCE3F7989777D11E ON sub_category');
        $this->addSql('ALTER TABLE sub_category CHANGE category_id_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F79812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_BCE3F79812469DE2 ON sub_category (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1C54C8C93');
        $this->addSql('DROP INDEX IDX_64C19C1C54C8C93 ON category');
        $this->addSql('ALTER TABLE category CHANGE type_id type_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1714819A0 FOREIGN KEY (type_id_id) REFERENCES type_category (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1714819A0 ON category (type_id_id)');
        $this->addSql('ALTER TABLE part DROP FOREIGN KEY FK_490F70C6C40FCFA8');
        $this->addSql('DROP INDEX IDX_490F70C6C40FCFA8 ON part');
        $this->addSql('ALTER TABLE part CHANGE piece_id piece_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part ADD CONSTRAINT FK_490F70C66DF71F3C FOREIGN KEY (piece_id_id) REFERENCES piece (id)');
        $this->addSql('CREATE INDEX IDX_490F70C66DF71F3C ON part (piece_id_id)');
        $this->addSql('ALTER TABLE piece DROP FOREIGN KEY FK_44CA0B235DC6FE57');
        $this->addSql('DROP INDEX IDX_44CA0B235DC6FE57 ON piece');
        $this->addSql('ALTER TABLE piece CHANGE subcategory_id subcategory_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B23F78A56EE FOREIGN KEY (subcategory_id_id) REFERENCES sub_category (id)');
        $this->addSql('CREATE INDEX IDX_44CA0B23F78A56EE ON piece (subcategory_id_id)');
        $this->addSql('ALTER TABLE sub_category DROP FOREIGN KEY FK_BCE3F79812469DE2');
        $this->addSql('DROP INDEX IDX_BCE3F79812469DE2 ON sub_category');
        $this->addSql('ALTER TABLE sub_category CHANGE category_id category_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE sub_category ADD CONSTRAINT FK_BCE3F7989777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_BCE3F7989777D11E ON sub_category (category_id_id)');
    }
}
