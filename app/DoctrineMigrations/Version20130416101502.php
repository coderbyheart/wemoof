<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130416101502 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE user CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE url url LONGTEXT DEFAULT NULL, CHANGE twitter twitter VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE talk CHANGE `order` `order` INT NOT NULL");
        $this->addSql("ALTER TABLE event ADD xing LONGTEXT DEFAULT NULL, ADD facebook LONGTEXT DEFAULT NULL, ADD googleplus LONGTEXT DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

        $this->addSql("ALTER TABLE talk CHANGE `order` `order` INT DEFAULT NULL");
        $this->addSql("ALTER TABLE user CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE url url LONGTEXT NOT NULL, CHANGE twitter twitter VARCHAR(255) NOT NULL");
        $this->addSql("ALTER TABLE event DROP xing, DROP facebook, DROP googleplus");
    }
}
