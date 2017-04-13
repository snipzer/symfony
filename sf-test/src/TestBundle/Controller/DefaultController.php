<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TestBundle:Default:index.html.twig');
    }

    public function fooAction()
    {
        return new Response('Footest');
    }

    public function barAction()
    {
        return new Response('Bartest');
    }

    public function paramAction($testParam)
    {
        return new Response('Paramètre : ' . $testParam);
    }

    public function numberAction($number)
    {
        return new Response('Nombre : ' . $number);
    }
}


