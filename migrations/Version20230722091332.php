<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230722091332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE picture (
                id UUID NOT NULL,
                title TEXT NOT NULL,
                image VARCHAR(255) NOT NULL,
                image_min VARCHAR(255) NOT NULL,
                image_gray VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                drawn_year INT NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                drawn_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX picture_title_uidx ON picture (title)');
        $this->addSql('CREATE UNIQUE INDEX picture_image_uidx ON picture (image)');

        $this->addSql('
            CREATE TABLE tag (
                id UUID NOT NULL,
                name VARCHAR(100) NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX tag_name_uidx ON tag (name)');

        $this->addSql('
            CREATE TABLE picture_tag (
                picture_id UUID NOT NULL,
                tag_id UUID NOT NULL,
                PRIMARY KEY(picture_id, tag_id)
            )
        ');
        $this->addSql('CREATE INDEX picture_tag_picture_id_idx ON picture_tag (picture_id)');
        $this->addSql('CREATE INDEX picture_tag_tag_id_idx ON picture_tag (tag_id)');

        $this->addSql('ALTER TABLE picture_tag ADD CONSTRAINT picture_tag_picture_id_fk FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture_tag ADD CONSTRAINT picture_tag_tag_id_fk FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('
            CREATE TABLE project (
                id UUID NOT NULL,
                title TEXT NOT NULL,
                description TEXT DEFAULT NULL,
                link VARCHAR(255) DEFAULT NULL,
                role VARCHAR(255) NOT NULL,
                image VARCHAR(255) DEFAULT NULL,
                weight SMALLINT NOT NULL,
                archived BOOLEAN NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX project_title_uidx ON project (title)');

        $this->addSql('
            CREATE TABLE pull_request (
                id UUID NOT NULL,
                platform VARCHAR(100) NOT NULL,
                repository VARCHAR(255) NOT NULL,
                external_id VARCHAR(100) NOT NULL,
                title TEXT NOT NULL,
                body TEXT DEFAULT NULL,
                status VARCHAR(100) NOT NULL,
                commits INT DEFAULT NULL,
                additions INT DEFAULT NULL,
                deletions INT DEFAULT NULL,
                files INT DEFAULT NULL,
                created_year INT NOT NULL,
                external_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id)
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE picture_tag DROP CONSTRAINT picture_tag_picture_id_fk');
        $this->addSql('ALTER TABLE picture_tag DROP CONSTRAINT picture_tag_tag_id_fk');

        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE picture_tag');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE pull_request');
    }
}
