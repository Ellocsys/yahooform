<?php

namespace Dim\TodoBundle\Services;

// рассыльщик писем

class Mailer
{
    protected $mailer;
    protected $templating;

    public function __construct($mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendEmailUser($email_to, $email_from, $data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom($email_from)
            ->setTo($email_to)
            ->setBody($this->templating->render('DimTodoBundle:Email:feedbackUser.html.twig',
                ['data' => $data]))
            ->setContentType('text/html');

        return $this->mailer->send($message);
    }
}
