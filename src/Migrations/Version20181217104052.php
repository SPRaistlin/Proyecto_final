<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181217104052 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE foto DROP FOREIGN KEY FK_EADC3BE554F853F8');
        $this->addSql('DROP INDEX IDX_EADC3BE554F853F8 ON foto');
        $this->addSql('ALTER TABLE foto CHANGE nombre nombre VARCHAR(255) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL, CHANGE receta_id receta_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE foto ADD CONSTRAINT FK_EADC3BE5FF6693B5 FOREIGN KEY (receta_id_id) REFERENCES receta (id)');
        $this->addSql('CREATE INDEX IDX_EADC3BE5FF6693B5 ON foto (receta_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE foto DROP FOREIGN KEY FK_EADC3BE5FF6693B5');
        $this->addSql('DROP INDEX IDX_EADC3BE5FF6693B5 ON foto');
        $this->addSql('ALTER TABLE foto CHANGE nombre nombre VARCHAR(250) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE slug slug VARCHAR(250) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE receta_id_id receta_id INT NOT NULL');
        $this->addSql('ALTER TABLE foto ADD CONSTRAINT FK_EADC3BE554F853F8 FOREIGN KEY (receta_id) REFERENCES receta (id)');
        $this->addSql('CREATE INDEX IDX_EADC3BE554F853F8 ON foto (receta_id)');
    }
}
