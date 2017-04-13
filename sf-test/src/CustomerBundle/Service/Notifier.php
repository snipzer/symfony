<?php

namespace CustomerBundle\Service;

class Notifier
{
    private $twig;

    private $mailer;

    private $recipient;

    /**
     * Notifier constructor.
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     * @param $recipient
     */
    public function __construct(
        \Twig_Environment $twig,
        \Swift_Mailer $mailer,
        $recipient
    ) {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->recipient = $recipient;
    }

    public function notifyContact($data)
    {
        $body = $this->twig->render(
            'CustomerBundle:Email:contact.html.twig',
            ['data' => $data]
        );

        $message = \Swift_Message::newInstance()
            ->setTo($this->recipient)
            ->setFrom($data['email'])
            ->setSubject($data['subject'])
            //->setBody($data['message'])
            ->setBody($body, 'text/html');

        return $this->mailer->send($message);
    }
}