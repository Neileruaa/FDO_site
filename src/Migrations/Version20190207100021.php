<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190207100021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE club CHANGE code_postal_club code_postal_club VARCHAR(10) DEFAULT NULL, CHANGE phone_club phone_club VARCHAR(10) DEFAULT NULL, CHANGE password password VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mailbox CHANGE message message VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat CHANGE team_id team_id INT DEFAULT NULL, CHANGE note note NUMERIC(10, 2) DEFAULT NULL, CHANGE nb_gardes nb_gardes NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE competition CHANGE club_organizer_id club_organizer_id INT DEFAULT NULL, CHANGE description description VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE dancer CHANGE email_dancer email_dancer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE row CHANGE dance_id dance_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL, CHANGE num_tour num_tour VARCHAR(255) DEFAULT NULL, CHANGE formation formation VARCHAR(255) DEFAULT NULL, CHANGE piste piste VARCHAR(255) DEFAULT NULL, CHANGE is_done is_done TINYINT(1) DEFAULT NULL, CHANGE passage_simul passage_simul INT DEFAULT NULL, CHANGE nb_judge nb_judge INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team CHANGE club_id club_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE club CHANGE code_postal_club code_postal_club VARCHAR(10) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE phone_club phone_club VARCHAR(10) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password password VARCHAR(100) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE competition CHANGE club_organizer_id club_organizer_id INT DEFAULT NULL, CHANGE description description VARCHAR(1000) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE dancer CHANGE email_dancer email_dancer VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE mailbox CHANGE message message VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE resultat CHANGE team_id team_id INT DEFAULT NULL, CHANGE note note NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE nb_gardes nb_gardes NUMERIC(10, 0) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE row CHANGE dance_id dance_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL, CHANGE num_tour num_tour VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE formation formation VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE piste piste VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE is_done is_done TINYINT(1) DEFAULT \'NULL\', CHANGE passage_simul passage_simul INT DEFAULT NULL, CHANGE nb_judge nb_judge INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team CHANGE club_id club_id INT DEFAULT NULL');
    }
}
