<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObsFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('taxon', EntityType::class, array(
                 'class' => 'AppBundle\Entity\Taxon',
                 'choice_label' => 'nomLatin',
                 'choice_value' => 'nomLatin',
                 'multiple' => false,
             ))
             ->add('longitude', NumberType::class, array(
                'scale' => 10,
            ))
            ->add('latitude', NumberType::class, array(
                'scale' => 11,
            ))
            ->add('date', DateTimeType::class, array (
                'data' => new \DateTime(),
            ))
            ->add('description', TextareaType::class)
            //->add('photoPath', FileType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Observation'
        ));
    }
}
