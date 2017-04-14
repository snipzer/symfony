<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // @var AuthenticationException $error (Erreur d'authentification)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisie
        $lastUsername = $authenticationUtils->getLastUsername();

        $redirect = $request->query->get('redirect');

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
            'redirect' => $redirect,
        ));

    }
}