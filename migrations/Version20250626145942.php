<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626145942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD sender_id INT DEFAULT NULL, ADD receiver_id INT DEFAULT NULL, DROP user, CHANGE content content VARCHAR(500) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FCD53EDB6 ON message (receiver_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6BD307FF624B39D ON message
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6BD307FCD53EDB6 ON message
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD user VARCHAR(255) NOT NULL, DROP sender_id, DROP receiver_id, CHANGE content content LONGTEXT NOT NULL
        SQL);
    }
}
