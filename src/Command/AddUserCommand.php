<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Command;

use Negromovich\AuthenticationBundle\Repository\AuthUserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddUserCommand extends Command
{
    private AuthUserRepository $authUserRepository;

    public function __construct(string $name = null, AuthUserRepository $authUserRepository)
    {
        parent::__construct($name ?? 'negromovich:auth:add-user');
        $this->authUserRepository = $authUserRepository;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'User\'s email');
        $this->addArgument('roles', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'User\'s roles', ['ROLE_ADMIN']);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $roles = $input->getArgument('roles');
        $user = $this->authUserRepository->createUserWithEmail($email);
        $roles && $user->setRoles($roles);
        $this->authUserRepository->saveUser($user);
        return 0;
    }
}
