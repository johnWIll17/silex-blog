<?php
namespace Blog\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text',  array(
                'constraints' => new Assert\Email()
            ))
            ->add('password', 'password', array(
                'constraints' => array(
                    new Assert\Length(array('min' => 6, 'max' => 32)),
                )
            ))
            ->add('role', 'choice', array(
                'choices' => array('ROLE_ADMIN'=> 'Admin', 'ROLE_SUBSCRIBER'=>'Subscriber', 'ROLE_AUTHOR' => 'Author'),
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('register', 'submit')
        ;

    }

    public function getName()
    {
        return 'register';
    }

}