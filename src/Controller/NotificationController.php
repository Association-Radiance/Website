<?php

namespace App\Controller;

use App\DTO\HelloAsso\DonationDataDTO;
use App\DTO\HelloAsso\WebhookDTO;
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
        private readonly DenormalizerInterface $denormalizer,
        private readonly EntityManagerInterface $entityManager,
        private readonly DonationRepository $donationRepository
    ) {}

    #[Route("/notification", name: "app_notification", methods: ["POST"])]
    public function index(Request $request): Response
    {
        $webhook = $this->serializer->deserialize($request->getContent(), WebhookDTO::class, "json");

        if ($webhook->eventType !== 'Payment') {
            return $this->json('not a donation');
        }

        $data = $this->denormalizer->denormalize($webhook->data, DonationDataDTO::class);

        if ($data->order->formType !== 'Donation') {
            return $this->json('not a donation');
        }

        if ($this->donationRepository->findOneBy(['helloAssoId' => $data->id])) {
            return $this->json('donation already registered');
        }

        $donation = new Donation();
        $donation->setHelloAssoId($data->id);
        $donation->setAmount($data->amount);

        $this->entityManager->persist($donation);
        $this->entityManager->flush();

        return $this->json('donation registered');
    }
}
