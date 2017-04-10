<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/blog", name="blog_list")
     * @Method("GET")
     */
    public function listAction()
    {
        return new Response("List action");
    }

    /**
     * @Route("/blog/{slug}.{_format}",
     *     name="blog_detail",
     *     defaults = {"_format": "html"},
     *     requirements={
     *     "slug": "[a-z0-9-]+",
     *     "_format": "html|json"
     *      }
     * )
     * @Method("GET")
     */
    public function detailAction($slug, $_format)
    {
        return new Response("Slug = ".$slug."\n Format = ".$_format);
    }
}
