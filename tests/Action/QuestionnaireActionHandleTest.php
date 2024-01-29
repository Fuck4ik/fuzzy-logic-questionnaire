<?php

declare(strict_types=1);

namespace App\Tests\Action;

use App\Action\QuestionnaireActionHandle;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use App\Repository\QuestionRepository;
use App\Repository\UserAnswerRepository;
use App\Service\FuzzyLogicInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @covers \App\Action\QuestionnaireActionHandle
 *
 * @internal
 */
class QuestionnaireActionHandleTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testHandleException(): void
    {
        // arrange
        $user = new User(Uuid::v7(), 'test');
        $question = new Question('test?');
        $question->addAnswer('correct', true);

        $questionRepository = $this->createMock(QuestionRepository::class);
        $questionRepository->method('getShuffledAll')->willReturn([$question]);
        $userAnswerRepository = $this->createMock(UserAnswerRepository::class);
        $userAnswerRepository->method('save');
        $fuzzyLogic = $this->createMock(FuzzyLogicInterface::class);
        $fuzzyLogic->method('check')->willReturn(true);

        $action = new QuestionnaireActionHandle(
            $questionRepository,
            $userAnswerRepository,
            $fuzzyLogic,
        );

        // assert
        $this->expectExceptionObject(new \RuntimeException('Нужно выбрать хотя бы один вариант ответа'));

        // act
        $action->handle($user, fn (Question $question, array $answers) => [])->rewind();
    }

    /**
     * @throws Exception
     */
    public function testAnswering(): void
    {
        // arrange
        $user = new User(Uuid::v7(), 'test');
        $question = new Question('test?');
        $correctAnswer = new Answer($question, 'correct', true);
        $fuzzyLogic = $this->createMock(FuzzyLogicInterface::class);
        $fuzzyLogic->method('check')->willReturn(true);
        $action = new QuestionnaireActionHandle(
            $this->createMock(QuestionRepository::class),
            $this->createMock(UserAnswerRepository::class),
            $fuzzyLogic,
        );
        // act
        $userAnswer = $action->answering($user, $question, $correctAnswer);

        // assert
        $this->assertSame($userAnswer->getUser(), $user);
        $this->assertSame($userAnswer->getQuestion(), $question);
        $this->assertSame($userAnswer->getAnswers(), [$correctAnswer]);
        $this->assertTrue($userAnswer->isCorrect());
    }
}
