<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217024102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE public.chats (id SERIAL NOT NULL, freelancer_id INT NOT NULL, employer_id INT NOT NULL, linked_to_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, status INT DEFAULT 1 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, linked_to VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1CB9796B8031A592 ON public.chats (linked_to_id)');
        $this->addSql('CREATE TABLE public.config (id SERIAL NOT NULL, label VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE public.employers (id SERIAL NOT NULL, user_id INT NOT NULL, status INT NOT NULL, default_contact_form JSON DEFAULT \'{}\', created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BAD72B1A76ED395 ON public.employers (user_id)');
        $this->addSql('CREATE TABLE public.feedbacks (id SERIAL NOT NULL, recipient_id INT NOT NULL, author_id INT NOT NULL, rating DOUBLE PRECISION NOT NULL, title VARCHAR(64) DEFAULT NULL, body TEXT DEFAULT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4AC00AAEE92F8F78 ON public.feedbacks (recipient_id)');
        $this->addSql('CREATE INDEX IDX_4AC00AAEF675F31B ON public.feedbacks (author_id)');
        $this->addSql('CREATE TABLE public.freelancers (id SERIAL NOT NULL, user_id INT NOT NULL, status INT NOT NULL, default_contact_form JSON DEFAULT \'{}\', created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_39E5C97AA76ED395 ON public.freelancers (user_id)');
        $this->addSql('CREATE TABLE public.messages (id SERIAL NOT NULL, chat_id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, body TEXT NOT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_19EEC5FF1A9A7125 ON public.messages (chat_id)');
        $this->addSql('CREATE TABLE public.projects (id SERIAL NOT NULL, employer_id INT NOT NULL, title VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, required_experience INT DEFAULT NULL, tags JSON NOT NULL, contact_form JSON NOT NULL, description TEXT DEFAULT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9E7F68CD41CD9E7A ON public.projects (employer_id)');
        $this->addSql('CREATE TABLE public.resumes (id SERIAL NOT NULL, freelancer_id INT NOT NULL, title VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, experience INT NOT NULL, tags JSON NOT NULL, description TEXT DEFAULT NULL, contact_form JSON NOT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F69C638F8545BDF5 ON public.resumes (freelancer_id)');
        $this->addSql('CREATE TABLE public.user_groups (id SERIAL NOT NULL, label VARCHAR(64) NOT NULL, level INT NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE public.users (id SERIAL NOT NULL, user_group_id INT NOT NULL, login VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, full_name VARCHAR(255) DEFAULT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2552C48D1ED93D47 ON public.users (user_group_id)');
        $this->addSql('ALTER TABLE public.employers ADD CONSTRAINT FK_8BAD72B1A76ED395 FOREIGN KEY (user_id) REFERENCES public.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.freelancers ADD CONSTRAINT FK_39E5C97AA76ED395 FOREIGN KEY (user_id) REFERENCES public.users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.messages ADD CONSTRAINT FK_19EEC5FF1A9A7125 FOREIGN KEY (chat_id) REFERENCES public.chats (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.projects ADD CONSTRAINT FK_9E7F68CD41CD9E7A FOREIGN KEY (employer_id) REFERENCES public.employers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.resumes ADD CONSTRAINT FK_F69C638F8545BDF5 FOREIGN KEY (freelancer_id) REFERENCES public.freelancers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.users ADD CONSTRAINT FK_2552C48D1ED93D47 FOREIGN KEY (user_group_id) REFERENCES public.user_groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE public.employers DROP CONSTRAINT FK_8BAD72B1A76ED395');
        $this->addSql('ALTER TABLE public.freelancers DROP CONSTRAINT FK_39E5C97AA76ED395');
        $this->addSql('ALTER TABLE public.messages DROP CONSTRAINT FK_19EEC5FF1A9A7125');
        $this->addSql('ALTER TABLE public.projects DROP CONSTRAINT FK_9E7F68CD41CD9E7A');
        $this->addSql('ALTER TABLE public.resumes DROP CONSTRAINT FK_F69C638F8545BDF5');
        $this->addSql('ALTER TABLE public.users DROP CONSTRAINT FK_2552C48D1ED93D47');
        $this->addSql('DROP TABLE public.chats');
        $this->addSql('DROP TABLE public.config');
        $this->addSql('DROP TABLE public.employers');
        $this->addSql('DROP TABLE public.feedbacks');
        $this->addSql('DROP TABLE public.freelancers');
        $this->addSql('DROP TABLE public.messages');
        $this->addSql('DROP TABLE public.projects');
        $this->addSql('DROP TABLE public.resumes');
        $this->addSql('DROP TABLE public.user_groups');
        $this->addSql('DROP TABLE public.users');
    }
}
