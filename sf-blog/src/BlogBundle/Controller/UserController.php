<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\User;
use BlogBundle\Form\UserType;
use Doctrine\DBAL\Driver\PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package BlogBundle\Controller
 */
class UserController extends Controller
{
    public function indexAction()
    {

        return new Response("UserCreated");
    }

    public function createUserAction(Request $request)
    {
        // Récupère le manager
        $manager = $this->getDoctrine()->getManager();

        // Créer un nouvel user
        $user = new User();

        // Récupère le formulaire
        $form = $this->createForm(new UserType(), $user);



        // Remplis le formulaire via la request
        $form->handleRequest($request);

        // Une fois que le formulaire est envoyer
        if($form->isSubmitted() && $form->isValid())
        {
            $encoder = $this->container->get("security.password_encoder");

            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());

            $user->setPassword($encoded);
            try
            {
                // Sauvegarde l'user
                $manager->persist($user);
                $manager->flush();
                // Envois un message à l'utilisateur
                $this->addFlash('notice', 'User created');
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        return $this->render("BlogBundle:Blog:inscription.html.twig", ['form' => $form->createView()]);
    }

}
