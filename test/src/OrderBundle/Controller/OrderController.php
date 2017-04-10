<?php

namespace OrderBundle\Controller;

use Doctrine\DBAL\Driver\PDOException;
use OrderBundle\Entity\Cart;
use OrderBundle\Entity\Line;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository("OrderBundle:Cart");

        $carts = $repository->findAll();
        return $this->render("OrderBundle:Order:list.html.twig", ["cart_list" => $carts]);
    }

    public function detailAction(Request $request)
    {
        $cartId = $request->attributes->get("id");
        $cartRepository = $this->getDoctrine()->getRepository("OrderBundle:Cart");
        $lineRepository = $this->getDoctrine()->getRepository("OrderBundle:Line");

        $cart = $cartRepository->findOneBy(["id" => $cartId]);
        $lines = $lineRepository->findBy(["cart" => $cartId]);

        if($cart == null)
        {
            throw $this->createNotFoundException("La page que vous recherchez n'existe pas");
        }

        return $this->render("OrderBundle:Order:detail.html.twig", ["cart" => $cart, "lines"=>$lines]);
    }

    public function updateAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository("OrderBundle:Cart");

        $cart = $repository->findOneBy(["id" => $request->attributes->get("id")]);

        if($cart == null)
        {
            throw $this->createNotFoundException("La page que vous rechercher n'existe pas");
        }

        $cart->setNumber($request->attributes->get("slug"));

        $em = $this->getDoctrine()->getManager();

        $em->persist($cart);
        $em->flush();

        return $this->render("OrderBundle:Order:detail.html.twig", ["cart" => $cart]);
    }

    public function deleteAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository("OrderBundle:Cart");

        $cart = $repository->findOneBy(["id" => $request->attributes->get("id")]);

        if($cart == null)
        {
            throw $this->createNotFoundException("La page que vous rechercher n'existe pas");
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($cart);
        $em->flush();

        return $this->render("OrderBundle:Order:detail.html.twig", ["cart" => $cart]);
    }

    public function createAction(Request $request)
    {
        $number = "1003";
        $date = new \DateTime("-2 days");
        $status = "pending";
        try
        {

            $cart = new Cart();
            $cart->setNumber($number);
            $cart->setDate($date);
            $cart->setStatus($status);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush();

        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
        return new Response("Created");
    }

    public function addLineAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository("OrderBundle:Cart");

        $cart = $repository->findOneBy(["id" => $request->attributes->get("id")]);

        if($cart == null)
        {
            throw $this->createNotFoundException("La page que vous rechercher n'existe pas");
        }

        $line = new Line();
        $line->setDesignation("Super produit")
            ->setPrice(144.2)
            ->setQuantity(15);

        $cart->addLines($line);

        $em = $this->getDoctrine()->getManager();

        $em->persist($cart);
        $em->flush();

        $this->addFlash('notice', 'La ligne a été créée');

        $url = $this->generateUrl('order_detail', ['id' => $cart->getId()]);

        return $this->redirect($url);
    }

    public function sideBarAction($max = 3)
    {
        $carts = $this->getDoctrine()->getRepository("OrderBundle:Cart")->findBy([], [], $max);

        return $this->render("OrderBundle:Fragment:sideBar.html.twig", ['carts' => $carts]);
    }

}
