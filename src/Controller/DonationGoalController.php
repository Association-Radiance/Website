<?php

namespace App\Controller;

use App\Entity\DonationGoal;
use App\Form\DonationGoalType;
use App\Repository\DonationGoalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/donation/goal")]
#[IsGranted("ROLE_ADMIN")]
final class DonationGoalController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DonationGoalRepository $donationGoalRepository
    ) {}

    #[Route("/new", name: "donation_goal_new", methods: ["GET", "POST"])]
    public function new(Request $request): Response
    {
        $donationGoal = new DonationGoal();

        $form = $this->createForm(DonationGoalType::class, $donationGoal, [
            "action" => $this->generateUrl("donation_goal_new"),
            "method" => "POST"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($donationGoal);
            $this->entityManager->flush();

            $response = $this->render("donation/success.stream.html.twig", [
                "donationGoals" => $this->donationGoalRepository->findBy([], ["amount" => "ASC"])
            ]);

            $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

            return $response;
        }

        return $this->render("donation_goal/_form.html.twig", [
            "form" => $form,
            "title" => "Nouveau pallier de donation"
        ]);
    }

    #[Route("/{id}/edit", name: "donation_goal_edit", methods: ["GET", "POST"])]
    public function edit(DonationGoal $donationGoal, Request $request): Response
    {
        $form = $this->createForm(DonationGoalType::class, $donationGoal, [
            "action" => $this->generateUrl("donation_goal_edit", [
                "id" => $donationGoal->getId()
            ]),
            "method" => "POST"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $response = $this->render("donation_goal/success.stream.html.twig", [
                "donationGoals" => $this->donationGoalRepository->findBy([], ["amount" => "ASC"])
            ]);

            $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

            return $response;
        }

        return $this->render("donation_goal/_form.html.twig", [
            "form" => $form,
            "title" => "Modifier le pallier de donation"
        ]);
    }

    #[Route("/{id}/delete", name: "donation_goal_delete", methods: ["DELETE"])]
    public function delete(DonationGoal $donationGoal): Response
    {
        $this->entityManager->remove($donationGoal);
        $this->entityManager->flush();

        $response = $this->render("donation_goal/table.stream.html.twig", [
            "donationGoals" => $this->donationGoalRepository->findBy([], ["amount" => "ASC"])
        ]);

        $response->headers->set("Content-Type", "text/vnd.turbo-stream.html");

        return $response;
    }
}
