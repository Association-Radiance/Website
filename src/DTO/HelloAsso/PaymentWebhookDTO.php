<?php

namespace App\DTO\HelloAsso;

class PaymentWebhookDTO
{
    public function __construct(public string $eventType, public PaymentDataDTO $data) {}

    public function isPayment(): bool
    {
        return $this->eventType === "Payment";
    }

    public function getDonation(): ?ItemDTO
    {
        foreach ($this->data->items as $item) {
            if ($item->isDonation()) {
                return $item;
            }
        }

        return null;
    }
}
