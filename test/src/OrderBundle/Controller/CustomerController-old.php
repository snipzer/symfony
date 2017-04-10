<?php

namespace OrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class old extends Controller
{
    public function indexAction()
    {
        $customerUrl1 = $this->generateUrl('customer_detail', ["slug" => "titi"]);
        $customerUrl2 = $this->generateUrl('customer_detail', ['slug' => "tata"]);

        return new Response(
            '<html>' .
            '<body>' .
                '<p>Liste des clients</p>' .
                    '<ul>' .
                        '<li>' .
                            '<a href="' . $customerUrl1 . '">Client "Foo"</a>' .
                        '</li>' .
                        '<li>' .
                            '<a href="' . $customerUrl2 . '">Client "Bar"</a>' .
                        '</li>' .
                        '<li>'.
                        '<p><a href="'.$this->generateUrl("customer_redirect").'">redirection</a></p>'.
                        '</li>'.
                    '</ul>' .
            '</body>' .
            '</html>'
        );
    }

    public function detailAction($slug)
    {
        if($slug == "notFound")
        {
            throw $this->createNotFoundException('Client introuvable');
        }




        $urlRetour = $this->generateUrl('customer_list', ["page" => 12]);

        return new Response(
            '<html>' .
            '<body>' .
                '<p>Client "' . $slug . '"</p>' .
                '<p>' .
                    '<a href="' . $urlRetour . '">Retour à la liste</a>' .
                '</p>' .
            '</body>' .
            '</html>'
        );
    }

    public function redirectAction()
    {
        return $this->redirectToRoute("customer_list");
    }
}