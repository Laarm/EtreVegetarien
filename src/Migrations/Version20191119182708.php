<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191119182708 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE restaurant_avis (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT DEFAULT NULL, posted_by_id INT DEFAULT NULL, note VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4D873B94B1E7706E (restaurant_id), INDEX IDX_4D873B945A6D2235 (posted_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurant_avis ADD CONSTRAINT FK_4D873B94B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant_avis ADD CONSTRAINT FK_4D873B945A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE preference preference VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant CHANGE adresse adresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE telephone telephone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE magasin_avis ADD note VARCHAR(255) NOT NULL, CHANGE magasin_id magasin_id INT DEFAULT NULL, CHANGE posted_by_id posted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE magasin CHANGE adresse adresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE article_commentaire CHANGE article_id article_id INT DEFAULT NULL, CHANGE posted_by_id posted_by_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE restaurant_avis');
        $this->addSql('ALTER TABLE article_commentaire CHANGE posted_by_id posted_by_id INT DEFAULT NULL, CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE magasin CHANGE adresse adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE magasin_avis DROP note, CHANGE magasin_id magasin_id INT DEFAULT NULL, CHANGE posted_by_id posted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant CHANGE adresse adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE preference preference VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
