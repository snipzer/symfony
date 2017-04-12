<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CartController
 * @package ShopBundle\Controller
 */
class CartController extends Controller
{
    /**
     * Cart index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('ShopBundle:Cart:index.html.twig');
    }

    public function addAction()
    {
        return new Response("AddAction");
    }

    public function removeAction()
    {
        return new Response("RemoveAction");
    }

    public function incrementAction()
    {
        return new Response("IncrementAction");
    }

    public function decrementAction()
    {
        return new Response("DecrementAction");
    }
}
