<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130507154030 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        $this->addSql("ALTER TABLE user ADD public TINYINT(1) NOT NULL DEFAULT 0 AFTER twitter");
        $this->addSql("UPDATE user SET twitter = NULL WHERE LENGTH(twitter) = 0");
        $this->addSql("UPDATE user SET description = NULL WHERE LENGTH(description) = 0");
        $this->addSql("UPDATE user SET url = NULL WHERE LENGTH(url) = 0");
        $this->addSql("UPDATE user SET firstname = NULL WHERE LENGTH(firstname) = 0");
        $this->addSql("UPDATE user SET lastname = NULL WHERE LENGTH(lastname) = 0");
        $this->addSql("UPDATE user SET twitter = CONCAT('@', twitter) WHERE twitter IS NOT NULL");
        $this->addSql("UPDATE user SET public = 1 WHERE description IS NOT NULL");

    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        $this->addSql("ALTER TABLE user DROP public");
    }
}
