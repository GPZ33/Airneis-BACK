<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509132322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, category_id INT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, INDEX IDX_E01FBE6A4584665A (product_id), UNIQUE INDEX UNIQ_E01FBE6A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category DROP image');
        $this->addSql('ALTER TABLE product DROP images');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A4584665A');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A12469DE2');
        $this->addSql('DROP TABLE images');
        $this->addSql('ALTER TABLE category ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product ADD images LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\'');
    }
}
