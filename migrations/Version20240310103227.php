<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240310103227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, city VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, zip_code INT NOT NULL, adress VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_5CECC7BE79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_product_id INT NOT NULL, id_adress_id INT NOT NULL, state VARCHAR(255) NOT NULL, date DATE NOT NULL, price_total DOUBLE PRECISION NOT NULL, INDEX IDX_F529939879F37AE5 (id_user_id), INDEX IDX_F5299398E00EE68D (id_product_id), INDEX IDX_F52993984B3458B (id_adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE79F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939879F37AE5 FOREIGN KEY (id_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398E00EE68D FOREIGN KEY (id_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984B3458B FOREIGN KEY (id_adress_id) REFERENCES adress (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE79F37AE5');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939879F37AE5');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398E00EE68D');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984B3458B');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE `order`');
    }
}
