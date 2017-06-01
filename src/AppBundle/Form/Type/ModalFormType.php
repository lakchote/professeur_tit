<?php

namespace AppBundle\Form\Type;

use AppBundle\Validator\Constraints\CguChecked;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ModalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', EmailType::class)
            ->add('_password', PasswordType::class)
            ->add('register', RegisterFormType::class)
            ->add('naturaliste', CheckboxType::class, [
                'label' => 'Vous êtes naturaliste'
            ])
            ->add('remember_me', CheckboxType::class, [
                'label' => 'Se souvenir de moi'
            ])
            ->add('newsletter', CheckboxType::class, [
                'label' => 'S\'inscrire à la newsletter'
            ])
            ->add('cgu', CheckboxType::class, [
                'label' => 'Je reconnais avoir pris connaissance des CGU ainsi que de la Politique de confidentialité',
                'constraints' => array(new CguChecked())
            ])
            ->add('media', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default', 'Registration']
        ]);
    }
}
