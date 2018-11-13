<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181113145404 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(200) NOT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receta (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, categoria_id INT NOT NULL, nombre VARCHAR(250) NOT NULL, ingredientes LONGTEXT NOT NULL, preparacion LONGTEXT NOT NULL, dificultad VARCHAR(20) NOT NULL, created DATETIME NOT NULL, INDEX IDX_B093494EDB38439E (usuario_id), INDEX IDX_B093494E3397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(200) NOT NULL, apellidos VARCHAR(200) NOT NULL, email VARCHAR(250) NOT NULL, pass VARCHAR(250) NOT NULL, apodo VARCHAR(20) DEFAULT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receta ADD CONSTRAINT FK_B093494EDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE receta ADD CONSTRAINT FK_B093494E3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE receta DROP FOREIGN KEY FK_B093494E3397707A');
        $this->addSql('ALTER TABLE receta DROP FOREIGN KEY FK_B093494EDB38439E');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE receta');
        $this->addSql('DROP TABLE usuario');
    }
}
