<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180927203844 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tarif');
        $this->addSql('ALTER TABLE commande ADD date_commande DATETIME NOT NULL, ADD payer TINYINT(1) DEFAULT NULL, DROP code_jour, DROP validation, CHANGE mail mail VARCHAR(60) NOT NULL, CHANGE date_com date_visite DATE NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD date_naissance DATE NOT NULL, DROP date_de_naissance, DROP date_visite, CHANGE pays nationalite VARCHAR(2) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tarif (id INT AUTO_INCREMENT NOT NULL, age_min INT NOT NULL, age_max INT NOT NULL, prix_demi_journee NUMERIC(5, 2) NOT NULL, prix_journee NUMERIC(5, 2) NOT NULL, denomination VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD code_jour VARCHAR(11) NOT NULL COLLATE utf8mb4_unicode_ci, ADD validation TINYINT(1) NOT NULL, DROP date_commande, DROP payer, CHANGE mail mail VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE date_visite date_com DATE NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD date_visite DATE NOT NULL, CHANGE date_naissance date_de_naissance DATE NOT NULL, CHANGE nationalite pays VARCHAR(2) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
