<?php

namespace Blog\Form\Article;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('update')
            ->add('name', 'text', array(
                'constraints' => array(
                    new Assert\NotBlank(),
                )
            ))
            ->add('content', 'textarea', array(
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('update', 'submit')
        ;

    }

    public function getName()
    {
        return 'updatearticle';
    }
}