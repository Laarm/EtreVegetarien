<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191221152607 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE navbar (id INT AUTO_INCREMENT NOT NULL, href VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_comment ADD posted_by_id INT NOT NULL, DROP posted_by, CHANGE article_id article_id INT NOT NULL');
        $this->addSql('ALTER TABLE article_comment ADD CONSTRAINT FK_79A616DB5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_79A616DB5A6D2235 ON article_comment (posted_by_id)');
        $this->addSql('ALTER TABLE store_feedback CHANGE store_id store_id INT NOT NULL, CHANGE posted_by_id posted_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE store CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD bio VARCHAR(255) DEFAULT NULL, DROP prefenrence_created_at, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE preference preference VARCHAR(255) DEFAULT NULL, CHANGE facebook facebook VARCHAR(255) DEFAULT NULL, CHANGE instagram instagram VARCHAR(255) DEFAULT NULL, CHANGE youtube youtube VARCHAR(255) DEFAULT NULL, CHANGE twitter twitter VARCHAR(255) DEFAULT NULL, CHANGE preference_created_at preference_created_at DATETIME DEFAULT NULL, CHANGE role role VARCHAR(255) DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contact ADD phone VARCHAR(255) DEFAULT NULL, DROP telephone, CHANGE sujet subject VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_A8D351B35A6D2235');
        $this->addSql('ALTER TABLE meal DROP posted_by');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9C5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant_feedback DROP FOREIGN KEY FK_4D873B945A6D2235');
        $this->addSql('ALTER TABLE restaurant_feedback DROP FOREIGN KEY FK_4D873B94B1E7706E');
        $this->addSql('ALTER TABLE restaurant_feedback CHANGE restaurant_id restaurant_id INT NOT NULL, CHANGE posted_by_id posted_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE restaurant_feedback ADD CONSTRAINT FK_F28C8AB7B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant_feedback ADD CONSTRAINT FK_F28C8AB75A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article CHANGE category category VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE navbar');
        $this->addSql('ALTER TABLE article CHANGE category category VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE article_comment DROP FOREIGN KEY FK_79A616DB5A6D2235');
        $this->addSql('DROP INDEX IDX_79A616DB5A6D2235 ON article_comment');
        $this->addSql('ALTER TABLE article_comment ADD posted_by VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP posted_by_id, CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_comment RENAME INDEX idx_79a616db7294869c TO IDX_FD7302DB7294869C');
        $this->addSql('ALTER TABLE contact ADD telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, DROP phone, CHANGE subject sujet VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9C5A6D2235');
        $this->addSql('ALTER TABLE meal ADD posted_by VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_A8D351B35A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meal RENAME INDEX idx_9ef68e9c5a6d2235 TO IDX_A8D351B35A6D2235');
        $this->addSql('ALTER TABLE meal_favorites RENAME INDEX idx_5a0662fe5a6d2235 TO IDX_F45DACDE5A6D2235');
        $this->addSql('ALTER TABLE meal_favorites RENAME INDEX idx_5a0662fe639666d6 TO IDX_F45DACDE1D236AAA');
        $this->addSql('ALTER TABLE product_favorites RENAME INDEX idx_8ac9d678d050b9d4 TO IDX_4FC84F4BD050B9D4');
        $this->addSql('ALTER TABLE product_favorites RENAME INDEX idx_8ac9d678de18e50b TO IDX_4FC84F4B4FD8F9C3');
        $this->addSql('ALTER TABLE product_sync RENAME INDEX idx_a1c3d7db4584665a TO IDX_5B38895F347EFB');
        $this->addSql('ALTER TABLE product_sync RENAME INDEX idx_a1c3d7dbb092a811 TO IDX_5B3889520096AE3');
        $this->addSql('ALTER TABLE restaurant CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE restaurant_feedback DROP FOREIGN KEY FK_F28C8AB7B1E7706E');
        $this->addSql('ALTER TABLE restaurant_feedback DROP FOREIGN KEY FK_F28C8AB75A6D2235');
        $this->addSql('ALTER TABLE restaurant_feedback CHANGE restaurant_id restaurant_id INT DEFAULT NULL, CHANGE posted_by_id posted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant_feedback ADD CONSTRAINT FK_4D873B945A6D2235 FOREIGN KEY (posted_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE restaurant_feedback ADD CONSTRAINT FK_4D873B94B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant_feedback RENAME INDEX idx_f28c8ab7b1e7706e TO IDX_4D873B94B1E7706E');
        $this->addSql('ALTER TABLE restaurant_feedback RENAME INDEX idx_f28c8ab75a6d2235 TO IDX_4D873B945A6D2235');
        $this->addSql('ALTER TABLE store CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE store_feedback CHANGE store_id store_id INT DEFAULT NULL, CHANGE posted_by_id posted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_feedback RENAME INDEX idx_ec5e3ba2b092a811 TO IDX_3EC8B50620096AE3');
        $this->addSql('ALTER TABLE store_feedback RENAME INDEX idx_ec5e3ba25a6d2235 TO IDX_3EC8B5065A6D2235');
        $this->addSql('ALTER TABLE user ADD prefenrence_created_at DATETIME DEFAULT \'NULL\', DROP bio, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE preference preference VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE facebook facebook VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE instagram instagram VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE youtube youtube VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE twitter twitter VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE preference_created_at preference_created_at DATETIME DEFAULT \'NULL\', CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE avatar avatar VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
