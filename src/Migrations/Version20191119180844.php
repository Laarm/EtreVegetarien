<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191119180844 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE preference preference VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant CHANGE adresse adresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE telephone telephone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE magasin_avis DROP FOREIGN KEY FK_3EC8B50620096AE3');
        $this->addSql('ALTER TABLE magasin_avis DROP FOREIGN KEY FK_3EC8B5065A6D2235');
        $this->addSql('ALTER TABLE magasin_avis CHANGE magasin_id magasin_id INT DEFAULT NULL, CHANGE posted_by_id posted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE magasin_avis ADD CONSTRAINT FK_3EC8B50620096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magasin_avis ADD CONSTRAINT FK_3EC8B5065A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magasin CHANGE adresse adresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE article_commentaire CHANGE article_id article_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_commentaire CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE magasin CHANGE adresse adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE magasin_avis DROP FOREIGN KEY FK_3EC8B50620096AE3');
        $this->addSql('ALTER TABLE magasin_avis DROP FOREIGN KEY FK_3EC8B5065A6D2235');
        $this->addSql('ALTER TABLE magasin_avis CHANGE magasin_id magasin_id INT DEFAULT NULL, CHANGE posted_by_id posted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE magasin_avis ADD CONSTRAINT FK_3EC8B50620096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE magasin_avis ADD CONSTRAINT FK_3EC8B5065A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE restaurant CHANGE adresse adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE preference preference VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}