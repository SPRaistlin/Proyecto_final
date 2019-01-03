<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181220144642 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE foto (id INT AUTO_INCREMENT NOT NULL, receta_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_EADC3BE554F853F8 (receta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE foto ADD CONSTRAINT FK_EADC3BE554F853F8 FOREIGN KEY (receta_id) REFERENCES receta (id)');
        $this->addSql('ALTER TABLE receta DROP fotos');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE foto');
        $this->addSql('ALTER TABLE receta ADD fotos LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\'');
    }
}
