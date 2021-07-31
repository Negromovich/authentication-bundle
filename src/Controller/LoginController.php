<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Controller;

use Negromovich\AuthenticationBundle\DependencyInjection\AuthConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoginController
{
    private AuthConfig $authConfig;
    private Environment $twig;

    public function __construct(AuthConfig $authConfig, Environment $twig)
    {
        $this->authConfig = $authConfig;
        $this->authConfig->authRoute = 'negromovich_authentication_auth';
        $this->twig = $twig;
    }

    public function loginAction(?string $targetUrl = null): Response
    {
        return new Response(
            $this->twig->render(
                '@NegromovichAuthentication/login.html.twig',
                ['authConfig' => $this->authConfig]
            )
        );
    }

    public function logoutAction(string $targetUrl = '/'): Response
    {
        return new Response(
            $this->twig->render(
                '@NegromovichAuthentication/login.html.twig',
                ['authConfig' => $this->authConfig, 'logoutTargetUrl' => $targetUrl]
            )
        );
    }
}
