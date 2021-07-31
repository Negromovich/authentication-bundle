<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('roles');
        $resolver->addAllowedTypes('roles', 'array');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = array_combine(
            array_map(fn(string $role) => preg_replace('/^ROLE_/', '', $role), $options['roles']),
            $options['roles']
        );
        $builder->add('roles', ChoiceType::class, [
            'choices' => $roles,
            'multiple' => true,
            'expanded' => true,
        ]);
        $builder->add('save', SubmitType::class);
    }
}
