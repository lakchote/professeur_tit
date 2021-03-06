<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class OAuth2Controller extends Controller
{
    /**
     * @Route("/connect/google", name="connect_google")
     * @Method("GET")
     */
    public function connectGoogleAction()
    {
        $googleOAuthProvider = $this->get('app.google_provider');
        $url = $googleOAuthProvider->getAuthorizationUrl();
        return $this->redirect($url);
    }

    /**
     * @Route("/connect/google-check", name="connect_google_check")
     * @Method("GET")
     */
    public function connectGoogleCheckAction()
    {
    }

    /**
     * @Route("/connect/facebook", name="connect_facebook")
     * @Method("GET")
     */
    public function connectFacebookAction()
    {
        $facebookOAuthProvider = $this->get('app.facebook_provider');
        $url = $facebookOAuthProvider->getAuthorizationUrl([
            'scopes' => ['public_profile', 'email'],
        ]);

        return $this->redirect($url);
    }

    /**
     * @Route("/connect/facebook-check", name="connect_facebook_check")
     * @Method("GET")
     */
    public function connectFacebookActionCheckAction()
    {
    }
}
