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

#[Route('/donation')]
final class DonationController extends AbstractController
{
    public function __construct(
        private readonly DonationRepository $donationRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('', name: 'donation_index', methods: ['GET'])]
    public function index(): Response
    {
        $donations = $this->donationRepository->findAll();
        $total = $this->donationRepository->getTotalDonation();

        return $this->render('donation/_table.html.twig', [
            'total' => $total,
            'donations' => $donations
        ]);
    }

    #[Route('/new', name: 'donation_new', methods: ['GET'])]
    public function new(): Response
    {
        $donation = new Donation();

        $form = $this->createForm(DonationType::class, $donation, [
            'action' => $this->generateUrl('donation_create'),
            'method' => 'POST',
        ]);

        return $this->render('donation/_form.html.twig', [
            'donation' => $donation,
            'form' => $form
        ]);
    }

    #[Route('/create', name: 'donation_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $donation = new Donation();

        $form = $this->createForm(DonationType::class, $donation, [
            'action' => $this->generateUrl('donation_create'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                'donation/_form.html.twig',
                [
                    'donation' => $donation,
                    'form' => $form
                ],
                new Response('', Response::HTTP_UNPROCESSABLE_ENTITY)
            );
        }

        $this->entityManager->persist($donation);
        $this->entityManager->flush();

        return $this->render('donation/_row.html.twig', [
            'donation' => $donation
        ]);
    }
}
