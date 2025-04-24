<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424053352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE user_keys_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE action (id VARCHAR(255) NOT NULL, display VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE company (id INT NOT NULL, name VARCHAR(255) NOT NULL, rut VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, locality VARCHAR(255) DEFAULT NULL, notes TEXT DEFAULT NULL, state INT NOT NULL, logo VARCHAR(255) DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, max_concurrent_sessions INT DEFAULT NULL, account_validation_link_lifespan INT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE options (id VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE profile (id INT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_admin BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8157AA0F979B1AD6 ON profile (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permissions (profile_id INT NOT NULL, action_id VARCHAR(255) NOT NULL, PRIMARY KEY(profile_id, action_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2DEDCC6FCCFA12B8 ON permissions (profile_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2DEDCC6F9D32F035 ON permissions (action_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id INT NOT NULL, company_id INT DEFAULT NULL, profile_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, state INT NOT NULL, access_code INT NOT NULL, account_validated INT DEFAULT 0 NOT NULL, free_raider INT DEFAULT 0, email_sender INT DEFAULT 0, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649979B1AD6 ON "user" (company_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649CCFA12B8 ON "user" (profile_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_keys (id INT NOT NULL, user_id INT DEFAULT NULL, private_key VARCHAR(255) NOT NULL, expiration TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, used BOOLEAN NOT NULL, key_end VARCHAR(255) NOT NULL, last_activity TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CE0F0825A76ED395 ON user_keys (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6F9D32F035 FOREIGN KEY (action_id) REFERENCES action (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_keys ADD CONSTRAINT FK_CE0F0825A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE company_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE profile_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE user_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE user_keys_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE profile DROP CONSTRAINT FK_8157AA0F979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6FCCFA12B8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6F9D32F035
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649979B1AD6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649CCFA12B8
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_keys DROP CONSTRAINT FK_CE0F0825A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE action
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE company
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE options
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE profile
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE permissions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_keys
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
