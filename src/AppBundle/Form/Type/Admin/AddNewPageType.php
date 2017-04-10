<?php

namespace AppBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class AddNewPageType extends AbstractType
{
    private $seo;

    public function __construct($seo)
    {
        $this->seo = $seo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreRoute', ChoiceType::class, [
            'choices' => $this->seo->getAvailableRoutes(),
            'choice_label' => function ($value, $key, $index)
            {
                return $value;
            },
            'label' => 'Nouvelle page à ajouter'
            ])
            ->add('seo', ModifyPageType::class, [
                'label' => 'Données SEO'
            ])
        ;
    }
}
