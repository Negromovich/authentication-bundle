<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateUserForm extends EditUserForm
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', TextType::class);
        parent::buildForm($builder, $options);
    }
}
