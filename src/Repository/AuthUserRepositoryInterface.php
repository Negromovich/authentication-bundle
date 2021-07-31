<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Repository;

use Negromovich\AuthenticationBundle\Entity\AuthUserInterface;
use Negromovich\AuthenticationBundle\Security\FirebaseIdToken;

interface AuthUserRepositoryInterface
{
    public function getByFirebaseIdToken(FirebaseIdToken $firebaseIdToken): ?AuthUserInterface;

    /** @return array<AuthUserInterface> */
    public function findAllUsers(): array;

    public function findUserById($id): ?AuthUserInterface;

    public function saveUser(AuthUserInterface $user): void;

    public function createUserWithEmail(string $email): AuthUserInterface;
}
