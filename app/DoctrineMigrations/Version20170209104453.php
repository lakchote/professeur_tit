<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170209104453 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auteur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_55AB1405E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, ordre_id INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2473F2135E237E06 (name), INDEX IDX_2473F2139291498C (ordre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3B37B2E85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE observation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, taxon_id INT NOT NULL, date DATE NOT NULL, longitude NUMERIC(10, 8) NOT NULL, latitude NUMERIC(11, 8) NOT NULL, status VARCHAR(255) NOT NULL, photo_path VARCHAR(255) DEFAULT NULL, INDEX IDX_C576DBE0A76ED395 (user_id), INDEX IDX_C576DBE0DE13F470 (taxon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_737992C95E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rang (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_81CFA4CE5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxon (id INT AUTO_INCREMENT NOT NULL, ordre_id INT DEFAULT NULL, famille_id INT DEFAULT NULL, rang_id INT NOT NULL, habitat_id INT DEFAULT NULL, cdNom INT NOT NULL, taxSup INT DEFAULT NULL, cdSup INT DEFAULT NULL, cdRef INT NOT NULL, nomLatin VARCHAR(255) NOT NULL, nomVernaculaire VARCHAR(255) NOT NULL, nomVernaculaireEN VARCHAR(255) NOT NULL, annee INT DEFAULT NULL, urlINPN TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5B6723AB7965093 (cdNom), INDEX IDX_5B6723AB9291498C (ordre_id), INDEX IDX_5B6723AB97A77B84 (famille_id), INDEX IDX_5B6723AB3CC0D837 (rang_id), INDEX IDX_5B6723ABAFFE2D26 (habitat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taxon_auteur (taxon_id INT NOT NULL, auteur_id INT NOT NULL, INDEX IDX_FE38531DE13F470 (taxon_id), INDEX IDX_FE3853160BB6FE6 (auteur_id), PRIMARY KEY(taxon_id, auteur_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', reset_password VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F2139291498C FOREIGN KEY (ordre_id) REFERENCES ordre (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE0DE13F470 FOREIGN KEY (taxon_id) REFERENCES taxon (id)');
        $this->addSql('ALTER TABLE taxon ADD CONSTRAINT FK_5B6723AB9291498C FOREIGN KEY (ordre_id) REFERENCES ordre (id)');
        $this->addSql('ALTER TABLE taxon ADD CONSTRAINT FK_5B6723AB97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('ALTER TABLE taxon ADD CONSTRAINT FK_5B6723AB3CC0D837 FOREIGN KEY (rang_id) REFERENCES rang (id)');
        $this->addSql('ALTER TABLE taxon ADD CONSTRAINT FK_5B6723ABAFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id)');
        $this->addSql('ALTER TABLE taxon_auteur ADD CONSTRAINT FK_FE38531DE13F470 FOREIGN KEY (taxon_id) REFERENCES taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE taxon_auteur ADD CONSTRAINT FK_FE3853160BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE taxon_auteur DROP FOREIGN KEY FK_FE3853160BB6FE6');
        $this->addSql('ALTER TABLE taxon DROP FOREIGN KEY FK_5B6723AB97A77B84');
        $this->addSql('ALTER TABLE taxon DROP FOREIGN KEY FK_5B6723ABAFFE2D26');
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F2139291498C');
        $this->addSql('ALTER TABLE taxon DROP FOREIGN KEY FK_5B6723AB9291498C');
        $this->addSql('ALTER TABLE taxon DROP FOREIGN KEY FK_5B6723AB3CC0D837');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0DE13F470');
        $this->addSql('ALTER TABLE taxon_auteur DROP FOREIGN KEY FK_FE38531DE13F470');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE0A76ED395');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE habitat');
        $this->addSql('DROP TABLE observation');
        $this->addSql('DROP TABLE ordre');
        $this->addSql('DROP TABLE rang');
        $this->addSql('DROP TABLE taxon');
        $this->addSql('DROP TABLE taxon_auteur');
        $this->addSql('DROP TABLE user');
    }
}
