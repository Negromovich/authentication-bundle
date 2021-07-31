<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NegromovichAuthenticationBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
