<?php

namespace HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HelloBundle:Default:index.html.twig');
    }

    public function listAction()
    {
        return new Response("ListAction controller");
    }

    public function detailAction($slug, $_format)
    {
        return new Response("<p>DetailAction controller</p><p> Slug = ".$slug."</p><p>Format = ".$_format."</p>");
    }
}
