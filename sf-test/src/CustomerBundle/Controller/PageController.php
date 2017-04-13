<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function homeAction()
    {
        $speaker = $this->get('customer.speaker');
        $name = $speaker->sayMyName();


        return $this->render('CustomerBundle:Page:home.html.twig', [
            'random_name' => $name
        ]);
    }

    public function contactAction(Request $request)
    {
        $data = [
           'email' => null,
           'subject' => 'Test',
           'message' => null,
           'copy' => null,
           'send' => null,
        ];

        $form = $this
            ->createFormBuilder($data)
            ->add('email')
            ->add('subject', 'text', [
                'data' => 'FOO'
            ])
            ->add('service', 'choice', [
                // Attention: Clé/Valeur inversées version < 2.7
                'choices' => [
                    'Service commercial'  => 1,
                    'Service facturation' => 2,
                    'Service technique'   => 3,
                ],
                'choices_as_values' => true, // (Requis < 3.0)
                'expanded'    => true, // False by default, Select => Radio
                'multiple'    => true, // False by default, Radio => Checkboxes
                'placeholder' => 'Choisissez un service',
            ])
            ->add('message', 'textarea')
            ->add('copy', 'checkbox', [
                'required' => false,
            ])
            ->add('attachment', 'file', [
                'required' => false,
            ])
            ->add('send', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sent = $this
                ->get('customer.notifier')
                ->notify($form->getData());

            if (0 < $sent) {
                $this->addFlash('success', 'Email envoyé !');
            }

            /* Symfony\Component\HttpFoundation\File\UploadedFile */
            if ($file = $data['attachment']) {
                // $fileName = $file->getClientOriginalName();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                $dir = $this->getParameter('kernel.root_dir') . '/../var/data';

                // Move the file to the directory
                $file->move($dir, $fileName);
            }

            return $this->redirectToRoute('page_contact');
        }

        return $this->render('CustomerBundle:Page:contact.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
        ]);
    }
}
