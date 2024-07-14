<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240714072359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX pull_request_platform_repository_external_id_uidx ON pull_request (platform, repository, external_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX pull_request_platform_repository_external_id_uidx');
    }
}
