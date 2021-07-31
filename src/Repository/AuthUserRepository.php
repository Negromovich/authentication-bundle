<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Negromovich\AuthenticationBundle\Entity\AuthUser;
use Negromovich\AuthenticationBundle\Entity\AuthUserInterface;
use Negromovich\AuthenticationBundle\Security\FirebaseIdToken;

class AuthUserRepository extends ServiceEntityRepository implements AuthUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, ?string $entityClass = null)
    {
        parent::__construct($registry, $entityClass ?? AuthUser::class);
    }

    public function getByFirebaseIdToken(FirebaseIdToken $firebaseIdToken): AuthUserInterface
    {
        $email = $firebaseIdToken->getEmail();
        if ($email) {
            $user = $this->findOneBy(['email' => $email]);
        } else {
            $firebaseUserId = $firebaseIdToken->getUserId();
            $user = $this->findOneBy(['firebaseUserId' => $firebaseUserId]);
        }

        $user ??= $this->createNewUser();
        $user->setFirebaseIdToken($firebaseIdToken);
        return $user;
    }

    protected function createNewUser(): AuthUserInterface
    {
        $className = $this->getClassMetadata()->getReflectionClass()->getName();
        $entity = new $className;
        if ($entity instanceof AuthUserInterface) {
            $this->getEntityManager()->persist($entity);
            return $entity;
        }
        throw new \RuntimeException(
            sprintf(
                'Invalid entity class, must be implements "%s", got "%s"',
                AuthUserInterface::class,
                get_class($entity)
            )
        );
    }

    public function findAllUsers(): array
    {
        return $this->findAll();
    }

    public function findUserById($id): ?AuthUserInterface
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function saveUser(AuthUserInterface $user): void
    {
        $this->getEntityManager()->flush($user);
    }

    public function createUserWithEmail(string $email): AuthUserInterface
    {
        $className = $this->getClassMetadata()->getReflectionClass()->getName();
        $entity = new $className;
        $entity->setEmail($email);
        $this->getEntityManager()->persist($entity);
        return $entity;
    }
}
