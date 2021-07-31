<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Controller;

use Negromovich\AuthenticationBundle\Form\CreateUserForm;
use Negromovich\AuthenticationBundle\Form\EditUserForm;
use Negromovich\AuthenticationBundle\Repository\AuthUserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    private AuthUserRepositoryInterface $authUserRepository;
    private array $roles;

    public function __construct(AuthUserRepositoryInterface $authUserRepository, array $roles)
    {
        $this->authUserRepository = $authUserRepository;
        $this->roles = array_keys($roles);
    }

    public function listAction(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTH_ADMIN');

        $users = $this->authUserRepository->findAllUsers();
        return $this->render(
            '@NegromovichAuthentication/admin/list.html.twig',
            [
                'users' => $users,
                'formatRoles' => fn(array $roles) => array_map(
                    fn(string $role) => preg_replace('/^ROLE_/', '', $role),
                    array_filter($roles, fn(string $role) => $role !== 'ROLE_USER')
                ),
            ]
        );
    }

    public function createAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTH_ADMIN');

        $formData = ['roles' => $this->roles];
        $form = $this->createForm(CreateUserForm::class, $formData, ['roles' => $this->roles]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $roles = $form->get('roles')->getData();
            $user = $this->authUserRepository->createUserWithEmail($email);
            $user->setRoles($roles);
            $this->authUserRepository->saveUser($user);
            $this->addFlash('success', sprintf('User "%s" created successfully', $user->getUsername()));
            return $this->redirectToRoute('negromovich_authentication_admin_list');
        }

        return $this->render(
            '@NegromovichAuthentication/admin/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    public function editAction($userId, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTH_ADMIN');

        $user = $this->authUserRepository->findUserById($userId);
        if (!$user) {
            throw $this->createNotFoundException();
        }

        $formData = ['roles' => $user->getRoles()];
        $form = $this->createForm(EditUserForm::class, $formData, ['roles' => $this->roles]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);
            $this->authUserRepository->saveUser($user);
            $this->addFlash('success', sprintf('User "%s" updated successfully', $user->getUsername()));
            return $this->redirectToRoute('negromovich_authentication_admin_list');
        }

        return $this->render(
            '@NegromovichAuthentication/admin/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
