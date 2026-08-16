<?php

namespace App\Controller;

use App\DTO\HelloAsso\PaymentWebhookDTO;
use App\Entity\Donation;
use App\Repository\DonationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class NotificationController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly DonationRepository $donationRepository
    ) {}

    #[Route("/notification", name: "app_notification", methods: ["POST"])]
    public function index(Request $request): Response
    {
        $allowedIps = ["51.138.206.200", "4.233.135.234"];

        $clientIp = $request->getClientIp();

        if (!in_array($clientIp, $allowedIps, true)) {
            return $this->json("access forbidden", 403);
        }

        $webhook = $this->serializer->deserialize($request->getContent(), PaymentWebhookDTO::class, "json");

        if (!$webhook->isPayment()) {
            return $this->json("not a payment");
        }

        $donationData = $webhook->getDonation();

        if ($donationData === null) {
            return $this->json("no donation in items");
        }

        if ($this->donationRepository->findOneBy(["helloAssoId" => $donationData->id])) {
            return $this->json("donation already exist");
        }

        $donation = new Donation();
        $donation->setHelloAssoId($donationData->id);
        $donation->setAmount($donationData->amount);
        $donation->setDate($webhook->data->date);

        $this->entityManager->persist($donation);
        $this->entityManager->flush();

        return $this->json("donation registered");
    }
}
