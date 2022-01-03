<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220103144411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages ADD senderadmin_id INT DEFAULT NULL, ADD recipientadmin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E969D95A5A6 FOREIGN KEY (senderadmin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E966534B6F FOREIGN KEY (recipientadmin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_DB021E969D95A5A6 ON messages (senderadmin_id)');
        $this->addSql('CREATE INDEX IDX_DB021E966534B6F ON messages (recipientadmin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E969D95A5A6');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E966534B6F');
        $this->addSql('DROP INDEX IDX_DB021E969D95A5A6 ON messages');
        $this->addSql('DROP INDEX IDX_DB021E966534B6F ON messages');
        $this->addSql('ALTER TABLE messages DROP senderadmin_id, DROP recipientadmin_id');
    }
}
