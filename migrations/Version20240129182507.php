<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240129182507 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE user_answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_answers (id INT NOT NULL, question_id INT NOT NULL, is_correct BOOLEAN NOT NULL, answered_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_session_id UUID NOT NULL, user_username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8DDD80C1E27F6BF ON user_answers (question_id)');
        $this->addSql('COMMENT ON COLUMN user_answers.answered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_answers.user_session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE user_answer_answer (user_answer_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(user_answer_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_C6AADDD2AAD3C5E3 ON user_answer_answer (user_answer_id)');
        $this->addSql('CREATE INDEX IDX_C6AADDD2AA334807 ON user_answer_answer (answer_id)');
        $this->addSql('ALTER TABLE user_answers ADD CONSTRAINT FK_8DDD80C1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_answer_answer ADD CONSTRAINT FK_C6AADDD2AAD3C5E3 FOREIGN KEY (user_answer_id) REFERENCES user_answers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_answer_answer ADD CONSTRAINT FK_C6AADDD2AA334807 FOREIGN KEY (answer_id) REFERENCES answers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE user_answers_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_answers DROP CONSTRAINT FK_8DDD80C1E27F6BF');
        $this->addSql('ALTER TABLE user_answer_answer DROP CONSTRAINT FK_C6AADDD2AAD3C5E3');
        $this->addSql('ALTER TABLE user_answer_answer DROP CONSTRAINT FK_C6AADDD2AA334807');
        $this->addSql('DROP TABLE user_answers');
        $this->addSql('DROP TABLE user_answer_answer');
    }
}
