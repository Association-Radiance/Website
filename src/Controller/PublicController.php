<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublicController extends AbstractController
{
    public function __construct(private readonly MailerService $mailerService) {}

    #[Route("/", name: "public_home")]
    public function home(): Response
    {
        return $this->render("public/home.html.twig");
    }

    #[Route("/edition-2026", name: "public_edition2026")]
    public function edition_2026(): Response
    {
        return $this->render("public/edition-2026.html.twig");
    }

    #[Route("/editions-precedentes", name: "public_previous_editions")]
    public function previous_editions(): Response
    {
        return $this->render("public/previous-editions.html.twig");
    }

    #[Route("/donation", name: "public_donation")]
    public function donations(): Response
    {
        return $this->render("public/donation.html.twig");
    }

    #[Route("/contact", name: "public_contact")]
    public function contact(Request $request): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailer = $this->mailerService->sendContactEmail($contact);

            $this->addFlash($mailer["status"], $mailer["message"]);

            if ($mailer["status"] === "success") {
                return $this->redirectToRoute("public_contact");
            }
        }

        return $this->render("public/contact.html.twig", [
            "form" => $form
        ]);
    }
}
