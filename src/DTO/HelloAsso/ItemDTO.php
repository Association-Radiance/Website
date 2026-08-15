<?php

namespace App\DTO\HelloAsso;

class ItemDTO
{
    public function __construct(public int $id, public int $amount, public string $type) {}

    public function isDonation(): bool
    {
        return $this->type === "Donation";
    }
}
