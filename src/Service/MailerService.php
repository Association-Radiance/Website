<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

readonly class MailerService
{

    private string $contact_sender_address;
    private string $contact_recipient_address;

    public function __construct(private MailerInterface $mailerInterface, string $contact_sender_address, string $contact_recipient_address)
    {
        $this->contact_sender_address = $contact_sender_address;
        $this->contact_recipient_address = $contact_recipient_address;
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