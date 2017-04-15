<?php

namespace AppBundle\Form\Type\Admin;

use AppBundle\Entity\Observation;
use AppBundle\Entity\Taxon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalObsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taxon', EntityType::class, [
                'class' => Taxon::class,
                'choice_label' => 'nomLatin'
            ])
            ->add('date', DateType::class)
            ->add('status', ChoiceType::class, [
                'choices' =>
                    [
                        'validée' => Observation::OBS_VALIDATED,
                        'refusée' => Observation::OBS_REFUSED
                    ]
            ])
            ->add('image', FileType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Observation::class
        ]);
    }
}
