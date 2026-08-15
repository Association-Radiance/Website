<?php

namespace App\DTO\HelloAsso;

use DateTime;

class PaymentDataDTO
{
    /**
     * @param ItemDTO[] $items
     */
    public function __construct(public DateTime $date, public array $items) {}
}
