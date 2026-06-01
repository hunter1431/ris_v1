<?php

namespace App\DTOs;

use Illuminate\Support\Arr;

class RisData
{
    public function __construct(
        public array $header,
        public array $details,
    ) {
    }

    public static function fromArray(array $payload): self
    {
        return new self(
            Arr::except($payload, ['details']),
            $payload['details'] ?? [],
        );
    }
}
