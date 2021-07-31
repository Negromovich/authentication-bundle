<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Security;

class FirebaseToken
{
    private string $idToken;

    public function __construct(string $idToken)
    {
        $this->idToken = $idToken;
    }

    public function getIdToken(): string
    {
        return $this->idToken;
    }
}
