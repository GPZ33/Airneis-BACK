<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240521164458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP is_carrousel');
        $this->addSql('ALTER TABLE product DROP is_highlander');
        $this->addSql('ALTER TABLE user ADD verification_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images ADD is_carrousel TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD is_highlander TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` DROP verification_token');
    }
}
