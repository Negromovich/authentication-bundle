<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Negromovich\AuthenticationBundle\Security\FirebaseIdToken;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass="Negromovich\AuthenticationBundle\Repository\AuthUserRepository")
 */
class AuthUser implements AuthUserInterface
{
    /** @ORM\Id() @ORM\Column(type="string") */
    private string $id;

    /** @ORM\Column(type="string", unique=true, nullable=true) */
    private ?string $firebaseUserId;

    /** @ORM\Column(type="string", unique=true, nullable=true) */
    private ?string $email;

    /** @ORM\Column(type="string", nullable=true) */
    private ?string $name;

    /** @ORM\Column(type="json", nullable=true) */
    private ?array $firebaseIdToken;

    /** @ORM\Column(type="json") */
    private array $roles = [];

    public function __construct()
    {
        $this->id = (string)Uuid::v1();
    }

    public function setFirebaseIdToken(FirebaseIdToken $firebaseIdToken): void
    {
        $this->firebaseUserId = $firebaseIdToken->getUserId();
        $this->email = $firebaseIdToken->getEmail();
        $this->name = $firebaseIdToken->getName();
        $this->firebaseIdToken = $firebaseIdToken->jsonSerialize();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return $roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return '';
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->name ?? $this->email ?? $this->id;
    }

    public function eraseCredentials(): void
    {
        $this->firebaseUserId = null;
        $this->firebaseIdToken = null;
    }
}
