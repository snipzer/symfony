<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AppController
 * @package AppBundle\Controller
 */
class AppController extends Controller
{
    /**
     * App index action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $productRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle:Product");

        $products = $productRepository->findAll();

        return $this->render('AppBundle:App:index.html.twig', [
            'products' => $products,
        ]);
    }

    public function productAction(Request $request)
    {
        $productId = $request->attributes->get("productId");
        $productRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle:Product");
        $offerRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle:Offer");

        $product = $productRepository->findOneBy(['id' => $productId]);
        if(!$product)
        {
            throw $this->createNotFoundException("Sorry this is not the product you are looking for");
        }

        $offers = $offerRepository->findBy(['product' => $product]);


        return $this->render("AppBundle:App:product.html.twig", [
            "offers" => $offers,
        ]);
    }
}
