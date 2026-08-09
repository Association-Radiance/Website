<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Form\DonationType;
use App\Repository\DonationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/donations")]
final class DonationController extends AbstractController
{
    public function __construct(private readonly DonationRepository $donationRepository, private readonly EntityManagerInterface $entityManager) {}

    #[Route("/new", name: "donation_new", methods: ["GET"])]
    public function new(): JsonResponse
    {
        return $this->json(["donation" => ["id" => null, "helloAssoId" => null, "amount" => null, "date" => null]]);
    }

    #[Route("/create", name: "donation_create", methods: ["POST"])]
    public function create(Request $request): Response
    {
        $donation = new Donation();

        $form = $this->createForm(DonationType::class, $donation, [
            "action" => $this->generateUrl("donation_create"),
            "method" => "POST",
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render("donation/_form.html.twig", ["donation" => $donation, "form" => $form], new Response("", Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        $this->entityManager->persist($donation);
        $this->entityManager->flush();

        return $this->render("donation/_row.html.twig", [
            "donation" => $donation,
        ]);
    }

    #[Route("/{id}/edit", name: "donation_edit", methods: ["GET"])]
    public function edit(Donation $donation): Response
    {
        $form = $this->createForm(DonationType::class, $donation, [
            "action" => $this->generateUrl("donation_update", ["id" => $donation->getId()]),
            "method" => "PATCH",
        ]);

        return $this->render("donation/_form.html.twig", [
            "donation" => $donation,
            "form" => $form,
        ]);
    }

    #[Route("/{id}", name: "donation_update", methods: ["PATCH"])]
    public function update(Donation $donation, Request $request): Response
    {
        $form = $this->createForm(DonationType::class, $donation, [
            "action" => $this->generateUrl("donation_update", ["id" => $donation->getId()]),
            "method" => "PATCH",
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render("donation/_form.html.twig", ["donation" => $donation, "form" => $form], new Response("", Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        $this->entityManager->flush();

        return $this->render("donation/_row.html.twig", [
            "donation" => $donation,
        ]);
    }

    #[Route("/{id}", name: "donation_delete", methods: ["DELETE"])]
    public function delete(Donation $donation, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->isCsrfTokenValid("delete" . $donation->getId(), $data["token"] ?? "")) {
            return $this->json(["success" => false, "message" => "Invalid CSRF token."], Response::HTTP_FORBIDDEN);
        }

        $this->entityManager->remove($donation);
        $this->entityManager->flush();

        return $this->json([
            "success" => true,
        ]);
    }
}
