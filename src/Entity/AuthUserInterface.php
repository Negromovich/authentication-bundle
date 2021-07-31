<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Entity;

use Negromovich\AuthenticationBundle\Security\FirebaseIdToken;
use Symfony\Component\Security\Core\User\UserInterface;

interface AuthUserInterface extends UserInterface
{
    public function setFirebaseIdToken(FirebaseIdToken $firebaseIdToken): void;

    public function getId();

    public function getEmail(): ?string;

    public function setEmail(string $email): void;

    public function setRoles(array $roles): void;
}
