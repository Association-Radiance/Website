<?php

namespace App\DTO\HelloAsso;

class DonationDataDTO
{
    public function __construct(
        public OrderDTO $order,
        public int $id,
        public int $amount
    ) {}
}
