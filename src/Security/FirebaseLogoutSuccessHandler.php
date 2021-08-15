<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Security;

use Negromovich\AuthenticationBundle\Controller\LoginController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

// Using in success_handler option for Symfony < 5.1
class FirebaseLogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    private LoginController $loginController;

    public function __construct(LoginController $loginController)
    {
        $this->loginController = $loginController;
    }

    public function onLogoutSuccess(Request $request): Response
    {
        $targetUrl = $request->headers->get('Location', '/');
        return $this->loginController->logoutAction($targetUrl);
    }
}
