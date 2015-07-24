<?php
namespace Blog\Form\Article;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('create', 'submit')
        ;

    }

    public function getName()
    {
        return 'createarticle';
    }
}