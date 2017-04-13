<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\Customer;
use CustomerBundle\Form\CustomerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\IsTrue;

class CustomerController extends Controller
{
    public function indexAction()
    {
        $customers = $this
            ->getDoctrine()
            ->getRepository('CustomerBundle:Customer')
            ->findAll();

        return $this->render('CustomerBundle:Customer:index.html.twig', [
            'customers' => $customers,
        ]);
    }

    public function createAction(Request $request)
    {
        $customer = new Customer();

        $form = $this
            ->createForm(new CustomerType(), $customer, [
                'validation_groups' => ['registration']
            ])
            ->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('customer_index');
        }

        return $this->render( 'CustomerBundle:Customer:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function detailAction($id)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository('CustomerBundle:Customer')
            ->find($id);

        if (!$customer) {
            throw $this->createNotFoundException(
                'Customer not found'
            );
        }

        return $this->render('CustomerBundle:Customer:detail.html.twig', [
            'customer' => $customer,
        ]);
    }

    public function updateAction(Request $request)
    {
        $id = $request->attributes->get('id');

        $customer = $this
            ->getDoctrine()
            ->getRepository('CustomerBundle:Customer')
            ->find($id);

        $form = $this
            ->createForm(new CustomerType(), $customer)
            ->add('submit', 'submit');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('customer_detail', [
                'id' => $customer->getId(),
            ]);
        }

        return $this->render('CustomerBundle:Customer:update.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    public function deleteAction(Request $request)
    {
        $id = $request->attributes->get('id');

        $customer = $this
            ->getDoctrine()
            ->getRepository('CustomerBundle:Customer')
            ->find($id);

        $form = $this
            ->createFormBuilder()
            ->add('confirm', 'checkbox', [
                'label' => 'Confirmer la suppression ?',
                'required' => false,
                'constraints' => [
                    new IsTrue(),
                ]
            ])
            ->add('submit', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customer);
            $em->flush();

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('CustomerBundle:Customer:delete.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }
}
