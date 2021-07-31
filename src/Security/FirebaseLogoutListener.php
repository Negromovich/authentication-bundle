<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Security;

use Negromovich\AuthenticationBundle\Controller\LoginController;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class FirebaseLogoutListener
{
    private LoginController $loginController;

    public function __construct(LoginController $loginController)
    {
        $this->loginController = $loginController;
    }

    public function __invoke(LogoutEvent $event): void
    {
        $targetUrl = $event->getResponse()->headers->get('Location', '/');
        $response = $this->loginController->logoutAction($targetUrl);
        $event->setResponse($response);
    }
}
