<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Observation;
use AppBundle\Entity\User;
use AppBundle\Form\Type\Admin\ModalFreezeUserType;
use AppBundle\Form\Type\Admin\ModalObsType;
use AppBundle\Form\Type\Admin\ModalUserChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
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

    /**
     * @Route("/admin/modal/obs/{id}", name="admin_modal_obs")
     */
    public function modalObsAction(Observation $obs, Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $form = $this->createForm(ModalObsType::class, $obs);
            return $this->render('admin/modal/modify_obs.html.twig', [
                'form' => $form->createView(),
                'id' => $obs->getId()
            ]);
        }
        return new Response('', 400);
    }
}
