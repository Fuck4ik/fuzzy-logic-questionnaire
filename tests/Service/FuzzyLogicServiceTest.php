<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Service\FuzzyLogicService;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\FuzzyLogicService
 *
 * @internal
 */
class FuzzyLogicServiceTest extends TestCase
{
    private Answer $correctAnswer;
    private Answer $wrongAnswer;

    protected function setUp(): void
    {
        $question = new Question('test?');
        $this->correctAnswer = new Answer($question, 'correct', true);
        $this->wrongAnswer = new Answer($question, 'wrong', false);
    }

    public function testNoAnswers(): void
    {
        // arrange
        $fuzzyLogicService = new FuzzyLogicService();
        // act
        $result = $fuzzyLogicService->check();
        // assert
        $this->assertFalse($result);
    }

    public function testOneCorrectAnswer(): void
    {
        // arrange
        $fuzzyLogicService = new FuzzyLogicService();
        // act
        $result = $fuzzyLogicService->check($this->correctAnswer);
        // assert
        $this->assertTrue($result);
    }

    public function testOneWrongAnswer(): void
    {
        // arrange
        $fuzzyLogicService = new FuzzyLogicService();
        // act
        $result = $fuzzyLogicService->check($this->wrongAnswer);
        // assert
        $this->assertFalse($result);
    }

    public function testMixedAnswers(): void
    {
        // arrange
        $fuzzyLogicService = new FuzzyLogicService();
        // act
        $result = $fuzzyLogicService->check($this->correctAnswer, $this->wrongAnswer);
        // assert
        $this->assertFalse($result);
    }

    public function testMoreCorrectAnswers(): void
    {
        // arrange
        $fuzzyLogicService = new FuzzyLogicService();
        // act
        $result = $fuzzyLogicService->check($this->correctAnswer, $this->correctAnswer);
        // assert
        $this->assertTrue($result);
    }

    public function testMoreWrongAnswers(): void
    {
        // arrange
        $fuzzyLogicService = new FuzzyLogicService();
        // act
        $result = $fuzzyLogicService->check($this->wrongAnswer, $this->wrongAnswer);
        // assert
        $this->assertFalse($result);
    }
}
