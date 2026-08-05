<?php

namespace App\Controller;

use App\DTO\HelloAsso\DonationWebhookDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class NotificationController extends AbstractController
{
    #[Route("/notification", name: "app_notification", methods: ["POST"])]
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $webhook = $serializer->deserialize($request->getContent(), DonationWebhookDTO::class, "json");

        if ($webhook->data->hasDonationItem()) {
            $amount = $webhook->data->amount->total;

            return $this->json($amount);
        }

        return $this->json("");
    }
}
