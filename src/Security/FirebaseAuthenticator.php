<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Security;

use Kreait\Firebase\Auth;
use Negromovich\AuthenticationBundle\Entity\AuthUserInterface;
use Negromovich\AuthenticationBundle\Repository\AuthUserRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FirebaseAuthenticator extends AbstractGuardAuthenticator
{
    private const FIREBASE_ID_TOKEN = 'firebaseIdToken';

    private AuthUserRepositoryInterface $authUserRepository;
    private Auth $firebaseAuth;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        AuthUserRepositoryInterface $authUserRepository,
        Auth $firebaseAuth,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->authUserRepository = $authUserRepository;
        $this->firebaseAuth = $firebaseAuth;
        $this->urlGenerator = $urlGenerator;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('negromovich_authentication_login'));
    }

    public function supports(Request $request): bool
    {
        return $request->get(self::FIREBASE_ID_TOKEN) !== null;
    }

    public function getCredentials(Request $request): FirebaseToken
    {
        return new FirebaseToken($request->get(self::FIREBASE_ID_TOKEN));
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?AuthUserInterface
    {
        if ($credentials instanceof FirebaseToken) {
            $token = $this->firebaseAuth->verifyIdToken($credentials->getIdToken());
            $firebaseIdToken = new FirebaseIdToken($token->claims()->all());
            $user = $this->authUserRepository->getByFirebaseIdToken($firebaseIdToken);
            $this->authUserRepository->saveUser($user);
            return $user;
        }
        return null;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if ($credentials instanceof FirebaseToken) {
            try {
                $this->firebaseAuth->verifyIdToken($credentials->getIdToken());
                return true;
            } catch (\Exception $exception) {
                throw new AuthenticationException('Invalid credentials', 0, $exception);
            }
        }
        return false;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->start($request, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return new Response('OK');
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
