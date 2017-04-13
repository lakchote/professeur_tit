<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\Admin\ModalFreezeUserType;
use AppBundle\Form\Type\Admin\ModalUserChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminModalController extends Controller
{
    /**
     * @Route("/admin/modal/user/freeze/{id}", name="admin_modal_user_freeze")
     */
    public function modalUserFreezeAction(User $user, Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $form = $this->createForm(ModalFreezeUserType::class, $user);
            return $this->render('admin/modal/freeze_user.html.twig', [
                'form' => $form->createView(),
                'id' => $user->getId()
            ]);
        }
        return new Response('', 400);
    }

    /**
     * @Route("/admin/modal/user/{id}/changePassword", name="admin_modal_user_changePassword")
     */
    public function modalUserChangePasswordAction(User $user, Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $form = $this->createForm(ModalUserChangePasswordType::class, $user);
            return $this->render('admin/modal/change_password_user.html.twig', [
                'form' => $form->createView(),
                'id' => $user->getId()
            ]);
        }
        return new Response('', 400);
    }
}
