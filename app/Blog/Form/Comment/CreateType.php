<?php

namespace Blog\Form\Comment;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;
class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'textarea', array(
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('submit', 'submit')
        ;

    }

    public function getName()
    {
        return 'createcomment';
    }
}