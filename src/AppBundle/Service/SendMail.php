<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 06/02/2017
 * Time: 14:06
 */

namespace AppBundle\Service;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\TwigEngine;

class SendMail
{
    private $mailer;
    private $twig;
    private $em;

    public function __construct(\Swift_Mailer $mailer, TwigEngine $twig, EntityManager $em)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $em;
    }

    public function sendResetPasswordMail($email) {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        $username = $user->getUsername();
        $bytes = openssl_random_pseudo_bytes(4);
        $resetString = bin2hex($bytes);
        $user->setResetPassword($resetString);
        $this->em->persist($user);
        $this->em->flush();
        $message = new \Swift_Message();
        $message->setSubject('Réinitialisation de votre mot de passe ProfesseurTit')
                ->setFrom('prof@professeurtit.com')
                ->setTo($email)
                ->setBody($this->twig->render('mail/reset_password.html.twig', [
                    'username' => $username,
                    'resetString' => $resetString
                ]), 'text/html');
        $this->mailer->send($message);
    }
}