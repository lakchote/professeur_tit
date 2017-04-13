<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\Admin\ModalFreezeUserType;
use AppBundle\Form\Type\Admin\ModalUserChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nbSignalements = $em->getRepository('AppBundle:User')->countFrozenUsers();
        $nbNaturalistesEnAttente= $em->getRepository('AppBundle:User')->countNaturalistesEnAttente();
        return $this->render('admin/home.html.twig', [
            'nbSignalements'  => $nbSignalements,
            'nbNaturalistesEnAttente' => $nbNaturalistesEnAttente
        ]);
    }

    /**
     * @Route("/admin/signalements/list", name="admin_signalements_list")
     */
    public function listSignalementsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $signalements = $em->getRepository('AppBundle:User')->getFrozenUsers();
        return $this->render('admin/signalements_list.html.twig', [
            'signalements' => $signalements
        ]);
    }

    /**
     * @Route("/admin/signalement/delete/{id}", name="admin_signalement_delete")
     */
    public function removeSignalementAction(User $id)
    {
        $this->get('app.manager.user')->removeSignalement($id);
        $this->addFlash('success', 'Le signalement a été supprimé.');
        return new Response('', 200);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     */
    public function removeUserAction(User $id)
    {
        $this->get('app.manager.user')->removeUser($id);
        $this->addFlash('success', 'L\'utilisateur a été supprimé.');
        return new Response('', 200);
    }

    /**
     * @Route("/admin/user/freeze/{id}", name="admin_user_freeze")
     */
    public function freezeUserAction(User $user, Request $request)
    {
        $form = $this->createForm(ModalFreezeUserType::class, $user);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $this->get('app.manager.user')->freezeUser($form->getData());
            $this->addFlash('success', 'L\'utilisateur a été banni.');
            return new Response('', 200);
        }
        return new Response($this->get('templating')->render('admin/modal/freeze_user.html.twig', [
                'form' => $form->createView(),
                'id' => $user->getId()
            ]), 400);
    }

    /**
     * @Route("/admin/users/list", name="admin_users_list")
     */
    public function usersListAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array(), array('date_inscription' => 'desc'));
        return $this->render('admin/users_list.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/user/{id}/changePassword", name="admin_user_changePassword")
     */
    public function userChangePasswordAction(User $user, Request $request)
    {
        $form = $this->createForm(ModalUserChangePasswordType::class, $user);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Le mot de passe a été modifié');
            return new Response('', 200);
        }
        return new Response($this->get('templating')->render('admin/modal/change_password_user.html.twig', [
            'form' => $form->createView(),
            'id' => $user->getId()
        ]), 400);
    }

    /**
     * @Route("/admin/naturalistes_en_attente/list", name="admin_naturalistes_attente_list")
     */
    public function naturalistesEnAttenteListAction()
    {
        $naturalistesEnAttente = $this->getDoctrine()->getRepository('AppBundle:User')->getNaturalistesEnAttente();
        return $this->render('admin/naturalistes_attente.html.twig', [
            'naturalistesEnAttente' => $naturalistesEnAttente
        ]);
    }

    /**
     * @Route("/admin/naturalistes_en_attente/valid/user/{id}", name="admin_naturaliste_attente_valid")
     */
    public function naturalisteEnAttenteValidAction(User $user)
    {
        $this->get('app.manager.user')->validNaturaliste($user);
        $this->addFlash('success', 'Le naturaliste a été validé');
        return new Response('', 200);
    }

    /**
     * @Route("/admin/naturalistes_en_attente/invalid/user/{id}", name="admin_naturaliste_attente_invalid")
     */
    public function naturalisteEnAttenteInvalidAction(User $user)
    {
        $this->get('app.manager.user')->invalidNaturaliste($user);
        $this->addFlash('success', 'Le naturaliste a été refusé');
        return new Response('', 200);
    }
}
