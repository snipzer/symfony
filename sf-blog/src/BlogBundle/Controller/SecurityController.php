<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $authenticationUtil = $this->get('security.authentication_utils');

        $error = $authenticationUtil->getLastAuthenticationError();

        $lastUsername = $authenticationUtil->getLastUsername();

        return $this->render('BlogBundle:security:login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
