<?php

namespace App\Entity;

use App\Repository\UserAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final
 *
 * Пользовательские ответы на вопросы
 */
#[ORM\Table(name: 'user_answers')]
#[ORM\Entity(repositoryClass: UserAnswerRepository::class)]
class UserAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Embedded(class: User::class)]
    private User $user;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Question $question;

    #[ORM\Column]
    private bool $isCorrect;

    /**
     * @var Collection<int, Answer>
     */
    #[ORM\ManyToMany(targetEntity: Answer::class)]
    private Collection $answers;

    #[ORM\Column]
    private \DateTimeImmutable $answeredAt;

    /**
     * @param Answer[] $answers
     */
    public function __construct(
        User $user,
        Question $question,
        bool $isCorrect,
        array $answers,
        ?\DateTimeImmutable $answeredAt = null
    ) {
        $this->user = $user;
        $this->question = $question;
        $this->isCorrect = $isCorrect;
        $this->answers = new ArrayCollection($answers);
        $this->answeredAt = $answeredAt ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers(): array
    {
        return $this->answers->toArray();
    }

    public function getAnsweredAt(): \DateTimeImmutable
    {
        return $this->answeredAt;
    }
}
