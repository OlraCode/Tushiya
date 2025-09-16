<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250915231719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_cart_product ON cart_item (user_id, course_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course ADD teacher_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course ADD CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_169E6FB941807E1D ON course (teacher_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE email email VARCHAR(60) NOT NULL, CHANGE name name VARCHAR(30) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_cart_product ON cart_item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL, CHANGE name name VARCHAR(65) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course DROP FOREIGN KEY FK_169E6FB941807E1D
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_169E6FB941807E1D ON course
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE course DROP teacher_id
        SQL);
    }
}
