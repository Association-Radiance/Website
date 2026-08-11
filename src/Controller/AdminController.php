<?php

namespace App\Controller;

use App\Repository\DonationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    public function __construct(private readonly DonationRepository $donationRepository) {}

    #[Route("/admin", name: "admin_index")]
    public function index(): Response
    {
        $donations = $this->donationRepository->findBy([], ["date" => "DESC"]);
        $total = $this->donationRepository->getTotalDonation();

        return $this->render("admin/index.html.twig", [
            "total" => $total,
            "donations" => $donations,
        ]);
    }
}
