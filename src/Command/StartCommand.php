<?php

namespace App\Command;

use App\Action\QuestionnaireActionHandle;
use App\Command\Style\AppOutputStyle;
use App\Command\Style\ChoiceQuestionFactory;
use App\Entity\Question;
use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'app:start',
    description: 'Команда для запуска приложения',
)]
final class StartCommand extends Command
{
    public function __construct(
        private readonly QuestionnaireActionHandle $questionnaireAction,
        private readonly ChoiceQuestionFactory $choiceQuestionFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Имя пользователя')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new AppOutputStyle($input, $output);

        $user = new User(Uuid::v7(), $input->getArgument('username'));

        $io->printWelcome($user->username);
        $tableCorrect = $io->createTableResult()->setHeaderTitle('Верные ответы');
        $tableWrong = $io->createTableResult()->setHeaderTitle('Неверные ответы');

        $askQuestion = fn (Question $question, array $answers) => $io->askQuestion(
            $this->choiceQuestionFactory->create($question, ...$answers)
        );
        foreach ($this->questionnaireAction->handle($user, $askQuestion) as $userAnswer) {
            $io->addTableResultRow(
                $userAnswer->isCorrect() ? $tableCorrect : $tableWrong,
                $userAnswer->getQuestion(),
                ...$userAnswer->getAnswers()
            );
        }

        $tableCorrect->render();
        $tableWrong->render();

        return Command::SUCCESS;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new AppOutputStyle($input, $output);

        if (!$input->getArgument('username')) {
            $input->setArgument('username', $io->askUsername());
        }
    }
}
