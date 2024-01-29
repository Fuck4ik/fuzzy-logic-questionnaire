<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240128185555 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE questions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answers (id INT NOT NULL, question_id INT NOT NULL, text TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_50D0C6061E27F6BF ON answers (question_id)');
        $this->addSql('COMMENT ON COLUMN answers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE questions (id INT NOT NULL, text TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN questions.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6061E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Считаю что миграции для схем, а не для данных, но здесь и сейчас удобнее было нейронкой сгенерировать insert'ы
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (1, '1 + 1 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (1, 1, '3', now(), false),
                                   (2, 1, '2', now(), true),
                                   (3, 1, '0', now(), false)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (2, '2 + 2 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (4, 2, '4', now(), true),
                                   (5, 2, '3 + 1', now(), true),
                                   (6, 2, '10', now(), false)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (3, '3 + 3 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (7, 3, '1 + 5', now(), true),
                                   (8, 3, '1', now(), false),
                                   (9, 3, '6', now(), true),
                                   (10, 3, '2 + 4', now(), true)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (4, '4 + 4 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (11, 4, '8', now(), true),
                                   (12, 4, '4', now(), false),
                                   (13, 4, '0', now(), false),
                                   (14, 4, '0 + 8', now(), true)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (5, '5 + 5 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (15, 5, '6', now(), false),
                                   (16, 5, '18', now(), false),
                                   (17, 5, '10', now(), true),
                                   (18, 5, '9', now(), false),
                                   (19, 5, '0', now(), false)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (6, '6 + 6 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (20, 6, '3', now(), false),
                                   (21, 6, '9', now(), false),
                                   (22, 6, '0', now(), false),
                                   (23, 6, '12', now(), true),
                                   (24, 6, '5 + 7', now(), true)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (7, '7 + 7 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (25, 7, '5', now(), false),
                                   (26, 7, '14', now(), true)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (8, '8 + 8 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (27, 8, '16', now(), true),
                                   (28, 8, '12', now(), false),
                                   (29, 8, '9', now(), false),
                                   (30, 8, '5', now(), false)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (9, '9 + 9 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (31, 9, '18', now(), true),
                                   (32, 9, '9', now(), false),
                                   (33, 9, '17 + 1', now(), true),
                                   (34, 9, '2 + 16', now(), true)
       ");
        $this->addSql("INSERT INTO questions (id, text, created_at) VALUES (10, '10 + 10 =', now())");
        $this->addSql("INSERT INTO answers (id, question_id, text, created_at, is_correct)
                            VALUES (35, 10, '0', now(), false),
                                   (36, 10, '2', now(), false),
                                   (37, 10, '8', now(), false),
                                   (38, 10, '20', now(), true)
       ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE answers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE questions_id_seq CASCADE');
        $this->addSql('ALTER TABLE answers DROP CONSTRAINT FK_50D0C6061E27F6BF');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE questions');
    }
}
