<?php

namespace App\DTO\HelloAsso;

class AmountDTO
{
    public function __construct(public int $total, public int $vat, public int $discount) {}
}
