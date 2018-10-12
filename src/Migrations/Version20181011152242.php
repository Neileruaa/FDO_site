<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181011152242 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE danseur_equipe (danseur_id INT NOT NULL, equipe_id INT NOT NULL, INDEX IDX_1295AC615942A4C7 (danseur_id), INDEX IDX_1295AC616D861B89 (equipe_id), PRIMARY KEY(danseur_id, equipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE danseur_equipe ADD CONSTRAINT FK_1295AC615942A4C7 FOREIGN KEY (danseur_id) REFERENCES danseur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE danseur_equipe ADD CONSTRAINT FK_1295AC616D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE danseur_equipe');
    }
}
