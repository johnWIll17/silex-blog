<?php
namespace Blog\Form\Article;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('delete')
            ->add('delete', 'submit')
        ;

    }

    public function getName()
    {
        return 'deletearticle';
    }
}