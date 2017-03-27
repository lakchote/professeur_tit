<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', TextType::class, [
                'label' => 'Sujet du message',
                'constraints' => array(new NotBlank())
            ])
            ->add('nom', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => array(new NotBlank())
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => array(new NotBlank())
            ])
            ->add('email', EmailType::class,  [
                'label' => 'Votre email',
                'constraints' => array(new NotBlank(), new Email())
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'constraints' => array(new NotBlank(), new Length(['min' => '100'])),
                'attr' => ['style' => 'height:200px;']
            ]);
    }
}