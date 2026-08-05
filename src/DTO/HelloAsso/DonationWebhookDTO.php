<?php

namespace App\DTO\HelloAsso;

class DonationWebhookDTO
{
    public function __construct(public string $eventType, public DonationDataDTO $data) {}
}
