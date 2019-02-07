<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190131124742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, ville_club VARCHAR(255) NOT NULL, code_postal_club VARCHAR(10) DEFAULT NULL, phone_club VARCHAR(10) DEFAULT NULL, name_club_owner VARCHAR(255) NOT NULL, password VARCHAR(100) DEFAULT NULL, email_club VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_B8EE387227D818F7 (email_club), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mailbox (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) DEFAULT NULL, club INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, note NUMERIC(10, 2) DEFAULT NULL, nb_gardes NUMERIC(10, 0) DEFAULT NULL, INDEX IDX_E7DB5DE2296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name_category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, etat VARCHAR(50) NOT NULL, INDEX IDX_97A0ADA3F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reglement (id INT AUTO_INCREMENT NOT NULL, pdf_file VARCHAR(255) NOT NULL, title VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition (id INT AUTO_INCREMENT NOT NULL, club_organizer_id INT DEFAULT NULL, date_competition DATE NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postal_code NUMERIC(5, 0) NOT NULL, title VARCHAR(255) NOT NULL, nb_max_team NUMERIC(4, 0) NOT NULL, description VARCHAR(1000) DEFAULT NULL, INDEX IDX_B50A2CB1FDD8E52A (club_organizer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition_team (competition_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_CAA3380D7B39D312 (competition_id), INDEX IDX_CAA3380D296CD8AE (team_id), PRIMARY KEY(competition_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition_dance (competition_id INT NOT NULL, dance_id INT NOT NULL, INDEX IDX_EBFCCFB97B39D312 (competition_id), INDEX IDX_EBFCCFB965D64EDD (dance_id), PRIMARY KEY(competition_id, dance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition_judge (competition_id INT NOT NULL, judge_id INT NOT NULL, INDEX IDX_E24CF1C27B39D312 (competition_id), INDEX IDX_E24CF1C2B7D66194 (judge_id), PRIMARY KEY(competition_id, judge_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dancer (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, name_dancer VARCHAR(255) NOT NULL, first_name_dancer VARCHAR(255) NOT NULL, date_birth_dancer DATE NOT NULL, email_dancer VARCHAR(255) DEFAULT NULL, is_authorized TINYINT(1) NOT NULL, INDEX IDX_B11CC8A961190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE judge (id INT AUTO_INCREMENT NOT NULL, name_judge VARCHAR(255) NOT NULL, first_name_judge VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dance (id INT AUTO_INCREMENT NOT NULL, name_dance VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE row (id INT AUTO_INCREMENT NOT NULL, dance_id INT DEFAULT NULL, category_id INT DEFAULT NULL, num_tour NUMERIC(5, 0) DEFAULT NULL, formation VARCHAR(255) DEFAULT NULL, piste VARCHAR(255) DEFAULT NULL, is_done TINYINT(1) DEFAULT NULL, passage_simul TINYINT(1) DEFAULT NULL, INDEX IDX_8430F6DB65D64EDD (dance_id), INDEX IDX_8430F6DB12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE row_team (row_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_BF6969E883A269F2 (row_id), INDEX IDX_BF6969E8296CD8AE (team_id), PRIMARY KEY(row_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, club_id INT DEFAULT NULL, category_id INT NOT NULL, is_present TINYINT(1) NOT NULL, size VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61F61190A32 (club_id), INDEX IDX_C4E0A61F12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_dance (team_id INT NOT NULL, dance_id INT NOT NULL, INDEX IDX_DF4DB42F296CD8AE (team_id), INDEX IDX_DF4DB42F65D64EDD (dance_id), PRIMARY KEY(team_id, dance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_dancer (team_id INT NOT NULL, dancer_id INT NOT NULL, INDEX IDX_C7078F70296CD8AE (team_id), INDEX IDX_C7078F70A7CAA267 (dancer_id), PRIMARY KEY(team_id, dancer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE2296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F675F31B FOREIGN KEY (author_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB1FDD8E52A FOREIGN KEY (club_organizer_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE competition_team ADD CONSTRAINT FK_CAA3380D7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_team ADD CONSTRAINT FK_CAA3380D296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_dance ADD CONSTRAINT FK_EBFCCFB97B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_dance ADD CONSTRAINT FK_EBFCCFB965D64EDD FOREIGN KEY (dance_id) REFERENCES dance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_judge ADD CONSTRAINT FK_E24CF1C27B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition_judge ADD CONSTRAINT FK_E24CF1C2B7D66194 FOREIGN KEY (judge_id) REFERENCES judge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dancer ADD CONSTRAINT FK_B11CC8A961190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE row ADD CONSTRAINT FK_8430F6DB65D64EDD FOREIGN KEY (dance_id) REFERENCES dance (id)');
        $this->addSql('ALTER TABLE row ADD CONSTRAINT FK_8430F6DB12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE row_team ADD CONSTRAINT FK_BF6969E883A269F2 FOREIGN KEY (row_id) REFERENCES row (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE row_team ADD CONSTRAINT FK_BF6969E8296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE team_dance ADD CONSTRAINT FK_DF4DB42F296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_dance ADD CONSTRAINT FK_DF4DB42F65D64EDD FOREIGN KEY (dance_id) REFERENCES dance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_dancer ADD CONSTRAINT FK_C7078F70296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_dancer ADD CONSTRAINT FK_C7078F70A7CAA267 FOREIGN KEY (dancer_id) REFERENCES dancer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F675F31B');
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB1FDD8E52A');
        $this->addSql('ALTER TABLE dancer DROP FOREIGN KEY FK_B11CC8A961190A32');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F61190A32');
        $this->addSql('ALTER TABLE row DROP FOREIGN KEY FK_8430F6DB12469DE2');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F12469DE2');
        $this->addSql('ALTER TABLE competition_team DROP FOREIGN KEY FK_CAA3380D7B39D312');
        $this->addSql('ALTER TABLE competition_dance DROP FOREIGN KEY FK_EBFCCFB97B39D312');
        $this->addSql('ALTER TABLE competition_judge DROP FOREIGN KEY FK_E24CF1C27B39D312');
        $this->addSql('ALTER TABLE team_dancer DROP FOREIGN KEY FK_C7078F70A7CAA267');
        $this->addSql('ALTER TABLE competition_judge DROP FOREIGN KEY FK_E24CF1C2B7D66194');
        $this->addSql('ALTER TABLE competition_dance DROP FOREIGN KEY FK_EBFCCFB965D64EDD');
        $this->addSql('ALTER TABLE row DROP FOREIGN KEY FK_8430F6DB65D64EDD');
        $this->addSql('ALTER TABLE team_dance DROP FOREIGN KEY FK_DF4DB42F65D64EDD');
        $this->addSql('ALTER TABLE row_team DROP FOREIGN KEY FK_BF6969E883A269F2');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE2296CD8AE');
        $this->addSql('ALTER TABLE competition_team DROP FOREIGN KEY FK_CAA3380D296CD8AE');
        $this->addSql('ALTER TABLE row_team DROP FOREIGN KEY FK_BF6969E8296CD8AE');
        $this->addSql('ALTER TABLE team_dance DROP FOREIGN KEY FK_DF4DB42F296CD8AE');
        $this->addSql('ALTER TABLE team_dancer DROP FOREIGN KEY FK_C7078F70296CD8AE');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE mailbox');
        $this->addSql('DROP TABLE resultat');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE reglement');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE competition_team');
        $this->addSql('DROP TABLE competition_dance');
        $this->addSql('DROP TABLE competition_judge');
        $this->addSql('DROP TABLE dancer');
        $this->addSql('DROP TABLE judge');
        $this->addSql('DROP TABLE dance');
        $this->addSql('DROP TABLE row');
        $this->addSql('DROP TABLE row_team');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_dance');
        $this->addSql('DROP TABLE team_dancer');
    }
}
