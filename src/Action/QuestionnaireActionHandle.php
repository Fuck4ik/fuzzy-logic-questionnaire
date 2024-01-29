<?php

declare(strict_types=1);

namespace App\Action;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\UserAnswer;
use App\Repository\QuestionRepository;
use App\Repository\UserAnswerRepository;
use App\Service\FuzzyLogicInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Класс отвечающий за бизнес логику главного действия приложения-опросника.
 */
final readonly class QuestionnaireActionHandle
{
    public function __construct(
        private QuestionRepository $questionRepository,
        private UserAnswerRepository $userAnswerRepository,
        private FuzzyLogicInterface $fuzzyLogicService,
        private LoggerInterface $logger = new NullLogger(),
    ) {}

    /**
     * @param callable(Question $question, Answer[] $answers): list<Answer> $askQuestion
     *
     * @return \Generator<int, UserAnswer>
     */
    public function handle(User $user, callable $askQuestion): \Generator
    {
        $this->loggingUserSession($user);

        $questions = $this->questionRepository->getShuffledAll();

        $userAnswers = [];
        foreach ($questions as $question) {
            $selectedAnswers = $askQuestion($question, $question->getShuffledAnswers());

            if (0 === \count($selectedAnswers)) {
                throw new \RuntimeException('Нужно выбрать хотя бы один вариант ответа');
            }

            yield $userAnswer = $this->answering($user, $question, ...$selectedAnswers);
            $userAnswers[] = $userAnswer;
        }

        $this->userAnswerRepository->save(...$userAnswers);
    }

    public function answering(User $user, Question $question, Answer ...$selectedAnswers): UserAnswer
    {
        $isCorrectAnswers = $this->fuzzyLogicService->check(...$selectedAnswers);

        return new UserAnswer(
            user: $user,
            question: $question,
            isCorrect: $isCorrectAnswers,
            answers: $selectedAnswers
        );
    }

    private function loggingUserSession(User $user): void
    {
        $this->logger->debug(sprintf(
            'Пользователь "%s" запустил приложение с идентификатором сессии "%s"',
            $user->username,
            (string) $user->sessionId
        ));
    }
}
