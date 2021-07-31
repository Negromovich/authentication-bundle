<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\DependencyInjection;

class AuthConfig
{
    public array $app;
    public array $ui;
    public string $uiSelector;
    public string $authRoute;
    public string $successRedirectRoute;

    public function __construct(array $data)
    {
        $this->app = $data['app'];
        $this->ui = $data['ui'];
        $this->uiSelector = $data['uiSelector'];
        $this->authRoute = $data['authRoute'];
        $this->successRedirectRoute = $data['successRedirectRoute'];
    }
}
