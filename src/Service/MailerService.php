<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{

    private ContainerBagInterface $params;

    public function __construct(private readonly MailerInterface $mailerInterface, ContainerBagInterface $params)
    {
        $this->params = $params;
    }

    public function sendContactEmail(Contact $contact): array
    {
        $email = (new TemplatedEmail())
            ->from($this->params->get('contact_sender_address'))
            ->to($this->params->get('contact_recipient_address'))
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