<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509095111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE679F37AE5');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6DD4481AD');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6E00EE68D');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('ALTER TABLE product ADD images LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', ADD details VARCHAR(255) DEFAULT NULL, DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, id_order_id INT DEFAULT NULL, id_product_id INT DEFAULT NULL, id_user_id INT NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_2530ADE6DD4481AD (id_order_id), INDEX IDX_2530ADE6E00EE68D (id_product_id), INDEX IDX_2530ADE679F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE679F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6DD4481AD FOREIGN KEY (id_order_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6E00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE product ADD image VARCHAR(255) NOT NULL, DROP images, DROP details');
    }
}
