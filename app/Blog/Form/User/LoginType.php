<?php
namespace Blog\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('password', 'password')
            ->add('login', 'submit')
        ;

    }

    public function getName()
    {
        return 'login';
    }
}