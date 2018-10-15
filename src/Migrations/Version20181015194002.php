<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181015194002 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE competition (id INT AUTO_INCREMENT NOT NULL, date_competition DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition_team (competition_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_CAA3380D7B39D312 (competition_id), INDEX IDX_CAA3380D296CD8AE (team_id), PRIMARY KEY(competition_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition_dance (competition_id INT NOT NULL, dance_id INT NOT NULL, INDEX IDX_EBFCCFB97B39D312 (competition_id), INDEX IDX_EBFCCFB965D64EDD (dance_id), PRIMARY KEY(competition_id, dance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dancer (id INT AUTO_INCREMENT NOT NULL, name_dancer VARCHAR(255) NOT NULL, first_name_dancer VARCHAR(255) NOT NULL, date_birth_dancer DATE NOT NULL, email_dancer VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name_category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dance (id INT AUTO_INCREMENT NOT NULL, name_dance VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competition_team ADD CONSTRAINT FK_CAA3380D7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_team ADD CONSTRAINT FK_CAA3380D296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_dance ADD CONSTRAINT FK_EBFCCFB97B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_dance ADD CONSTRAINT FK_EBFCCFB965D64EDD FOREIGN KEY (dance_id) REFERENCES dance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE38727B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE38723400837C FOREIGN KEY (dancers_id) REFERENCES dancer (id)');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE team_dance ADD CONSTRAINT FK_DF4DB42F296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_dance ADD CONSTRAINT FK_DF4DB42F65D64EDD FOREIGN KEY (dance_id) REFERENCES dance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_dancer ADD CONSTRAINT FK_C7078F70296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_dancer ADD CONSTRAINT FK_C7078F70A7CAA267 FOREIGN KEY (dancer_id) REFERENCES dancer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE competition_team DROP FOREIGN KEY FK_CAA3380D7B39D312');
        $this->addSql('ALTER TABLE competition_dance DROP FOREIGN KEY FK_EBFCCFB97B39D312');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE38727B39D312');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD7B39D312');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE38723400837C');
        $this->addSql('ALTER TABLE team_dancer DROP FOREIGN KEY FK_C7078F70A7CAA267');
        $this->addSql('ALTER TABLE competition_dance DROP FOREIGN KEY FK_EBFCCFB965D64EDD');
        $this->addSql('ALTER TABLE team_dance DROP FOREIGN KEY FK_DF4DB42F65D64EDD');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE competition_team');
        $this->addSql('DROP TABLE competition_dance');
        $this->addSql('DROP TABLE dancer');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE dance');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F61190A32');
        $this->addSql('ALTER TABLE team_dance DROP FOREIGN KEY FK_DF4DB42F296CD8AE');
        $this->addSql('ALTER TABLE team_dancer DROP FOREIGN KEY FK_C7078F70296CD8AE');
    }
}
