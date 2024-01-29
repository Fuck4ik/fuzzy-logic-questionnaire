<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Answer;

interface FuzzyLogicInterface
{
    public function check(Answer ...$answers): bool;
}
