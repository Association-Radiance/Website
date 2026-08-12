<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Form\DonationType;
use App\Repository\DonationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/donation")]
#[IsGranted("ROLE_ADMIN")]
final class DonationController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DonationRepository $donationRepository
    ) {}

    #[Route("/new", name: "donation_new", methods: ["GET", "POST"])]
    public function new(Request $request): Response
    {
        if ($response = $this->redirectIfNotTurboFrame($request)) {
            return $response;
        }

        $donation = new Donation();

        $form = $this->createForm(DonationType::class, $donation, [
            "action" => $this->generateUrl("donation_new"),
            "method" => "POST"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($donation);
            $this->entityManager->flush();

            $response = $this->render("donation/success.stream.html.twig", [
                "donations" => $this->donationRepository->findBy([], ["date" => "DESC"]),
                "total" => $this->donationRepository->getTotalDonation()
            ]);

            $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

            return $response;
        }

        return $this->render("donation/_form.html.twig", [
            "form" => $form,
            "title" => "Nouvelle donation"
        ]);
    }

    #[Route("/{id}/edit", name: "donation_edit", methods: ["GET", "POST"])]
    public function edit(Donation $donation, Request $request): Response
    {
        $form = $this->createForm(DonationType::class, $donation, [
            "action" => $this->generateUrl("donation_edit", [
                "id" => $donation->getId()
            ]),
            "method" => "POST"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $response = $this->render("donation/success.stream.html.twig", [
                "donations" => $this->donationRepository->findBy([], ["date" => "DESC"]),
                "total" => $this->donationRepository->getTotalDonation()
            ]);

            $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

            return $response;
        }

        return $this->render("donation/_form.html.twig", [
            "form" => $form,
            "title" => "Modifier la donation"
        ]);
    }

    #[Route("/{id}/delete", name: "donation_delete", methods: ["DELETE"])]
    public function delete(Donation $donation): Response
    {
        $this->entityManager->remove($donation);
        $this->entityManager->flush();

        $response = $this->render("donation/table.stream.html.twig", [
            "donations" => $this->donationRepository->findBy([], ["date" => "DESC"]),
            "total" => $this->donationRepository->getTotalDonation()
        ]);

        $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

        return $response;
    }

    private function redirectIfNotTurboFrame(Request $request): ?Response
    {
        if ($request->isMethod("GET") && !$request->headers->has("Turbo-Frame")) {
            return $this->redirectToRoute("admin_index");
        }

        return null;
    }
}
