<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @final
 *
 * Библиотека ответов на вопросы
 */
#[ORM\Table(name: 'answers')]
#[ORM\Entity]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private Question $question;

    #[ORM\Column(type: Types::TEXT)]
    private string $text;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private bool $isCorrect;

    public function __construct(Question $question, string $text, bool $isCorrect)
    {
        $this->question = $question;
        $this->text = $text;
        $this->isCorrect = $isCorrect;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }
}
