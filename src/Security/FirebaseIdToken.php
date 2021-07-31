<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Security;

class FirebaseIdToken implements \JsonSerializable
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }

    public function getUserId(): string
    {
        return $this->data['user_id'];
    }

    public function getName(): ?string
    {
        return $this->data['name'] ?? null;
    }

    public function getEmail(): ?string
    {
        return $this->data['email'] ?? null;
    }

    public function isEmailVerified(): bool
    {
        return $this->data['email_verified'] ?? false;
    }
}
