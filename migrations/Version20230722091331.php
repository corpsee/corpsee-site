<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230722091331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE admin (
                id UUID NOT NULL,
                username VARCHAR(180) NOT NULL,
                roles JSON NOT NULL,
                password VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX admin_username_uidx ON admin (username)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE admin');
    }
}
