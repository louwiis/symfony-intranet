<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221210003409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE classe (id INT NOT NULL, school_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8F87BF96C32A47EE ON classe (school_id)');
        $this->addSql('CREATE TABLE grade (id INT NOT NULL, classe_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_595AAE348F5EA509 ON grade (classe_id)');
        $this->addSql('CREATE TABLE lesson (id INT NOT NULL, classe_id INT NOT NULL, name VARCHAR(255) NOT NULL, teacher VARCHAR(255) NOT NULL, room VARCHAR(255) NOT NULL, date DATE NOT NULL, start_time TIME(0) WITHOUT TIME ZONE NOT NULL, end_time TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F87474F38F5EA509 ON lesson (classe_id)');
        $this->addSql('CREATE TABLE school (id INT NOT NULL, address_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F99EDABBF5B7AF75 ON school (address_id)');
        $this->addSql('CREATE TABLE student (id INT NOT NULL, address_id INT NOT NULL, classe_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthdate DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF33F5B7AF75 ON student (address_id)');
        $this->addSql('CREATE INDEX IDX_B723AF338F5EA509 ON student (classe_id)');
        $this->addSql('CREATE TABLE student_grade (id INT NOT NULL, student_id INT NOT NULL, grade_id INT NOT NULL, score INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D16DD7A9CB944F1A ON student_grade (student_id)');
        $this->addSql('CREATE INDEX IDX_D16DD7A9FE19A1A8 ON student_grade (grade_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE348F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F38F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE school ADD CONSTRAINT FK_F99EDABBF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF338F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_grade ADD CONSTRAINT FK_D16DD7A9CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_grade ADD CONSTRAINT FK_D16DD7A9FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE classe DROP CONSTRAINT FK_8F87BF96C32A47EE');
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT FK_595AAE348F5EA509');
        $this->addSql('ALTER TABLE lesson DROP CONSTRAINT FK_F87474F38F5EA509');
        $this->addSql('ALTER TABLE school DROP CONSTRAINT FK_F99EDABBF5B7AF75');
        $this->addSql('ALTER TABLE student DROP CONSTRAINT FK_B723AF33F5B7AF75');
        $this->addSql('ALTER TABLE student DROP CONSTRAINT FK_B723AF338F5EA509');
        $this->addSql('ALTER TABLE student_grade DROP CONSTRAINT FK_D16DD7A9CB944F1A');
        $this->addSql('ALTER TABLE student_grade DROP CONSTRAINT FK_D16DD7A9FE19A1A8');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_grade');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
