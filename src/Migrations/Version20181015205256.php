<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181015205256 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE dancer_team DROP FOREIGN KEY FK_BFE928E75942A4C7');
        $this->addSql('ALTER TABLE dancer_team DROP FOREIGN KEY FK_BFE928E76D861B89');
        $this->addSql('DROP TABLE dancer_team');
        $this->addSql('DROP TABLE danseur');
        $this->addSql('DROP TABLE equipe');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dancer_team (danseur_id INT NOT NULL, equipe_id INT NOT NULL, INDEX IDX_BFE928E75942A4C7 (danseur_id), INDEX IDX_BFE928E76D861B89 (equipe_id), PRIMARY KEY(danseur_id, equipe_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE danseur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL COLLATE utf8mb4_unicode_ci, age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, numero_dossard INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dancer_team ADD CONSTRAINT FK_BFE928E75942A4C7 FOREIGN KEY (danseur_id) REFERENCES danseur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dancer_team ADD CONSTRAINT FK_BFE928E76D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
    }
}
