<?php

namespace App\Command\Style;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Класс отвечающий за стиль отображения в cli среде.
 */
final class AppOutputStyle extends SymfonyStyle
{
    public function __construct(
        InputInterface $input,
        private OutputInterface $output,
    ) {
        parent::__construct($input, $output);
    }

    public function printWelcome(string $username): void
    {
        $this->block([
            sprintf(
                'Здравствуйте %s! Вы запустили приложение тестирования с нечеткой логикой.',
                $username
            ),
            'Для того чтобы ответить на вопросы, впишите варианты ответов через запятую и нажмите Enter.',
            'Пример: 1, 2, 3, 4',
        ]);
    }

    public function createTableResult(): Table
    {
        return $this->createTable()
            ->setHeaders(['Вопрос', 'Ответы'])
        ;
    }

    public function addTableResultRow(Table $table, Question $question, Answer ...$userAnswers): void
    {
        $table->addRow([
            $question->getText(),
            implode(', ', array_map(static fn (Answer $answer) => $answer->getText(), $userAnswers)),
        ]);
    }

    public function askUsername(): mixed
    {
        return $this->ask(
            'Пожалуйста, введите свое имя: ',
            null,
            static function (mixed $value) {
                if (empty($value)) {
                    throw new \InvalidArgumentException('Имя не может быть пустым');
                }
                if (strlen($value) > User::USERNAME_MAX_LENGTH) {
                    throw new \InvalidArgumentException(sprintf(
                        'Имя не может быть длиннее %s символов',
                        User::USERNAME_MAX_LENGTH
                    ));
                }

                return $value;
            },
        );
    }

    public function success($message): void
    {
        $this->writeln('<fg=green;options=bold,underscore>OK</> '.$message);
    }

    public function comment($message): void
    {
        $this->text($message);
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}
