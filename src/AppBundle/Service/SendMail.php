<?php

namespace AppBundle\Service;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Twig_Environment;

class SendMail
{
    private $mailer;
    private $twig;
    private $em;
    private $contactMail;

    public function __construct(\Swift_Mailer $mailer, Twig_Environment $twig, EntityManager $em, $contactMail)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $em;
        $this->contactMail = $contactMail;
    }

    public function sendResetPasswordMail($email) {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        $username = $user->getUsername();
        $bytes = openssl_random_pseudo_bytes(4);
        $resetString = bin2hex($bytes);
        $user->setResetPassword($resetString);
        $this->em->persist($user);
        $this->em->flush();
        $message = \Swift_Message::newInstance()
                ->setSubject('Réinitialisation de votre mot de passe ProfesseurTit')
                ->setFrom('prof@professeurtit.com')
                ->setTo($email)
                ->setBody($this->twig->render('mail/reset_password.html.twig', [
                    'username' => $username,
                    'resetString' => $resetString
                ]), 'text/html');
        $this->mailer->send($message);
    }

    public function sendContactMail($data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($data['sujet'])
            ->setFrom($data['email'])
            ->setTo($this->contactMail)
            ->setBody($this->twig->render('mail/contact_mail.html.twig', [
                'sujet' => $data['sujet'],
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'message' => $data['message']
            ]), 'text/html');
        $this->mailer->send($message);
    }
}
