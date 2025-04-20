<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly class MailerService
{
    public function __construct(private MailerInterface $mailerInterface)
    {
    }

    public function sendContactEmail(Contact $contact): array
    {
        $email = (new TemplatedEmail())
            ->to('axel.damart@hotmail.fr')
            ->subject("test")
            ->htmlTemplate('emails/contact.html.twig')
            ->locale('fr')
            ->context(['contact' => $contact]);

        try {
            $this->mailerInterface->send($email);
        } catch (TransportExceptionInterface $exception) {
            return ['status' => 'error', 'error' => $exception->getMessage(), 'message' => 'Une erreur est survenue'];
        }

        return ['status' => 'success', 'message' => 'Demande de contact envoyé'];
    }
}