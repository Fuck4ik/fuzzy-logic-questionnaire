<?php

declare(strict_types=1);

namespace App\Command\Style;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Фабрика для создания пользовательского интерфейса отображения вопросов в cli среде.
 */
final readonly class ChoiceQuestionFactory
{
    public function create(Question $question, Answer ...$answers): ChoiceQuestion
    {
        $choices = $this->createChoices(...$answers);
        $choiceQuestion = new ChoiceQuestion(
            $question->getText(),
            array_map(static fn (Answer $answer) => $answer->getText(), $choices)
        );
        $choiceQuestion->setMultiselect(true);
        $choiceQuestion->setAutocompleterValues([]);
        $choiceQuestion->setErrorMessage(
            $errorMessage = <<<'TXT'
Ответ не принят т.к. указан недопустимый вариант.
Укажите существующие номера вариантов через запятую. (Пример: 1, 2)
TXT
        );
        $choiceQuestion->setValidator(function ($selected) use ($choices, $errorMessage) {
            // Check for a separated comma values
            if (!preg_match('/^[^,]+(?:,[^,]+)*$/', (string) $selected)) {
                throw new InvalidArgumentException($errorMessage);
            }

            $result = [];
            $selectedChoices = explode(',', (string) $selected);
            foreach ($selectedChoices as $selectedChoice) {
                $result[] = $choices[$selectedChoice] ?? throw new InvalidArgumentException($errorMessage);
            }

            return $result;
        });

        return $choiceQuestion;
    }

    private function createChoices(Answer ...$answers): array
    {
        $choices = [];
        $number = 1;
        foreach ($answers as $answer) {
            $choices[$number] = $answer;
            ++$number;
        }

        return $choices;
    }
}
