<?php

namespace AppBundle\Form\Type\Admin;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModalFreezeUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'disabled' => true
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'disabled' => true
            ])
            ->add('raisonBan', TextType::class, [
                'label' => 'Raison du ban',
                'constraints' => array(new NotBlank())
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
