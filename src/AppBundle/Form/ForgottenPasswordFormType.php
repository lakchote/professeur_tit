<?php

namespace AppBundle\Form;

<<<<<<< HEAD
=======
use AppBundle\Entity\User;
>>>>>>> Closes #9, Closes #10
use AppBundle\Validator\Constraints\UsernameExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
<<<<<<< HEAD
=======
use Symfony\Component\OptionsResolver\OptionsResolver;
>>>>>>> Closes #9, Closes #10
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgottenPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre e-mail',
                'constraints' => array(new UsernameExists(), new NotBlank())
            ]);
    }
<<<<<<< HEAD
=======

    public function configureOptions(OptionsResolver $resolver)
    {

    }
>>>>>>> Closes #9, Closes #10
}
