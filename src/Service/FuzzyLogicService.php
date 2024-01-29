<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Answer;

/**
 * Сервис проверки правильности ответа на вопрос с нечеткой логикой.
 */
final readonly class FuzzyLogicService implements FuzzyLogicInterface
{
    public function check(Answer ...$answers): bool
    {
        if (0 === \count($answers)) {
            return false;
        }

        foreach ($answers as $answer) {
            if (!$answer->isCorrect()) {
                return false;
            }
        }

        return true;
    }
}
