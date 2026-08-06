<?php

namespace App\DTO\HelloAsso;

use DateTime;

class DonationDataDTO
{
    public function __construct(
        public OrderDTO $order,
        public int $id,
        public int $amount,
        public DateTime $date
    ) {}
}
