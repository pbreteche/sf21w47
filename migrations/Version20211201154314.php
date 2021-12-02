<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211201154314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Merge author and user tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE user FROM user 
                INNER JOIN author ON author.authenticated_as_id = user.id
                WHERE author.id IS NULL'
        );
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE user
                INNER JOIN author ON author.authenticated_as_id = user.id
                SET user.name = author.name, user.email = author.email'
        );
        $this->addSql('ALTER TABLE user CHANGE name name VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DAB69C8EF');
        $this->addSql('DROP TABLE author');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DAB69C8EF FOREIGN KEY (written_by_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, authenticated_as_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_BDAFD8C8E7927C74 (email), UNIQUE INDEX UNIQ_BDAFD8C8EBFD8092 (authenticated_as_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
//        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C8EBFD8092 FOREIGN KEY (authenticated_as_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
//        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DAB69C8EF');
//        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DAB69C8EF FOREIGN KEY (written_by_id) REFERENCES author (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
//        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
//        $this->addSql('ALTER TABLE user DROP name, DROP email');
    }
}
