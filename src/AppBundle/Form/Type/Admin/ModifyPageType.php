<?php

namespace AppBundle\Form\Type\Admin;

use AppBundle\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titrePage', TextType::class, [
                'label' => 'Titre de la page'
            ])
            ->add('description', TextType::class)
            ->add('keywords', TextType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class
        ]);
    }

}
