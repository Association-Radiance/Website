<?php

namespace App\DTO\HelloAsso;

class WebhookDTO
{
    public function __construct(public string $eventType, public array $data) {}
}
