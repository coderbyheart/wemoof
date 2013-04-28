<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130421163549 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE event ADD ticket_sales_start DATETIME DEFAULT NULL AFTER start, ADD num_tickets INT NOT NULL AFTER ticket_sales_start");
        $this->addSql("CREATE TABLE registration (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, user_id INT NOT NULL, confirmed DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_62A8A7A771F7E88B (event_id), INDEX IDX_62A8A7A7A76ED395 (user_id), UNIQUE INDEX event_user (event_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)");
        $this->addSql("ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE user ADD login_key VARCHAR(255) DEFAULT NULL AFTER twitter");
        $this->addSql("ALTER TABLE user ADD verified TINYINT(1) NOT NULL AFTER login_key");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A771F7E88B");
        $this->addSql("ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7A76ED395");
        $this->addSql("DROP TABLE registration");
        $this->addSql("ALTER TABLE event DROP num_tickets, DROP num_tickets");
        $this->addSql("ALTER TABLE user DROP login_key");
        $this->addSql("ALTER TABLE user DROP verified");
    }
}
