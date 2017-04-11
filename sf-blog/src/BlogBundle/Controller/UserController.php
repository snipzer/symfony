<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\User;
use Doctrine\DBAL\Driver\PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $manager = $this->getDoctrine()->getManager();

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('pseudo', TextType::class)
            ->add('isAdmin', ChoiceType::class, array(
                'choices' => array(
                    true => 'True',
                    false => 'False'
                ),
                'expanded' => true,
            ))
            ->add('submit', SubmitType::class, array('label' => 'Create User'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            try
            {
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('notice', 'User created');
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }

            return $this->render("BlogBundle:Blog:inscription.html.twig", ['form' => $form->createView()]);
        }

        return $this->render("BlogBundle:Blog:inscription.html.twig", ['form' => $form->createView()]);
    }

}
