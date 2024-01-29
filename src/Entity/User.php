<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV7;

/**
 * Информация о пользователе и сессии.
 */
#[ORM\Embeddable]
final readonly class User
{
    public const int USERNAME_MAX_LENGTH = 255;

    public function __construct(
        #[ORM\Column(type: 'uuid')]
        public UuidV7 $sessionId,
        #[ORM\Column(length: self::USERNAME_MAX_LENGTH)]
        public string $username,
    ) {}
}
