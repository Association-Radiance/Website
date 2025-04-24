<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{

    private mixed $contact_sender_address;
    private mixed $contact_recipient_address;

    public function __construct($contact_sender_address, $contact_recipient_address, private MailerInterface $mailerInterface)
    {
        $this->contact_sender_address = $contact_sender_address;
        $this->contact_recipient_address = $contact_recipient_address;
        $this->mailerInterface = $mailerInterface;
    }

    public function sendContactEmail(Contact $contact): array
    {
        $email = (new TemplatedEmail())
            ->from($this->contact_sender_address)
            ->to($this->contact_recipient_address)
            ->subject("Nouvelle demande de contact de " . $contact->getName())
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

