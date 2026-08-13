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

    #[Route("/contact", name: "public_contact")]
    public function contact(Request $request): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $response = $this->mailerService->sendContactEmail($contact);

            $this->addFlash($response["status"], $response["message"]);

            return $this->redirectToRoute("public_contact", ["_fragment" => "contact"]);
        }

        return $this->render("public/contact.html.twig", [
            "form" => $form
        ]);
    }
}
