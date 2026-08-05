<?php

namespace App\DTO\HelloAsso;

class DonationDataDTO
{
    public function __construct(
        public AmountDTO $amount,

        /** @var ItemDTO[] */
        public array $items,
    ) {}

    public function hasDonationItem(): bool
    {
        foreach ($this->items as $item) {
            if ($item->type === "Donation") {
                return true;
            }
        }

        return false;
    }
}
