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

#[Route("/donations")]
final class DonationController extends AbstractController
{
    #[Route("/new", name: "donation_new", methods: ["GET", "POST"])]
    public function new(Request $request, EntityManagerInterface $entityManager, DonationRepository $donationRepository): Response
    {
        $donation = new Donation();

        $form = $this->createForm(DonationType::class, $donation, [
            "action" => $this->generateUrl("donation_new"),
            "method" => "POST",
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donation);
            $entityManager->flush();

            $response = $this->render("donation/success.stream.html.twig", [
                "donations" => $donationRepository->findAll(),
                "total" => $donationRepository->getTotalDonation(),
            ]);

            $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

            return $response;
        }

        return $this->render("donation/_form.html.twig", [
            "form" => $form,
            "title" => "Nouvelle donation",
        ]);
    }

    #[Route("/{id}/edit", name: "donation_edit", methods: ["GET", "POST"])]
    public function edit(Donation $donation, Request $request, EntityManagerInterface $entityManager, DonationRepository $donationRepository): Response
    {
        $form = $this->createForm(DonationType::class, $donation, [
            "action" => $this->generateUrl("donation_edit", ["id" => $donation->getId()]),
            "method" => "POST",
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $response = $this->render("donation/success.stream.html.twig", [
                "donations" => $donationRepository->findAll(),
                "total" => $donationRepository->getTotalDonation(),
            ]);

            $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

            return $response;
        }

        return $this->render("donation/_form.html.twig", [
            "form" => $form,
            "title" => "Modifier la donation",
        ]);
    }

    #[Route("/{id}/delete", name: "donation_delete", methods: ["DELETE"])]
    public function delete(Donation $donation, Request $request, EntityManagerInterface $entityManager, DonationRepository $donationRepository): Response
    {
        $entityManager->remove($donation);
        $entityManager->flush();

        $response = $this->render("donation/table.stream.html.twig", [
            "donations" => $donationRepository->findAll(),
            "total" => $donationRepository->getTotalDonation(),
        ]);

        $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

        return $response;
    }
}
