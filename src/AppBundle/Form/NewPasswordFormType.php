<?php

namespace AppBundle\Form;

<<<<<<< HEAD
=======
use AppBundle\Entity\User;
>>>>>>> Closes #9, Closes #10
use AppBundle\Validator\Constraints\CheckLength;
use AppBundle\Validator\Constraints\UsernameExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
<<<<<<< HEAD
=======
use Symfony\Component\OptionsResolver\OptionsResolver;
>>>>>>> Closes #9, Closes #10

class NewPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => array(new UsernameExists(), new NotBlank()),
                'label' => 'Votre email'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
<<<<<<< HEAD
                'invalid_message' => 'Les deux mots de passe ne sont pas identiques.',
=======
                'invalid_message' => 'Les deux mots de passe ne sont pas identiques',
>>>>>>> Closes #9, Closes #10
                'constraints' => array(new NotBlank(), new CheckLength())
            ])
            ->add('resetPassword', TextType::class,[
                'constraints' => array(new NotBlank()),
                'label' => 'L\'identifiant que vous avez reçu par mail'
            ]);
    }
}
