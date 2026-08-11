<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class EurosToCentsTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return number_format($value / 100, 2, ".", "");
    }

    public function reverseTransform(mixed $value): ?int
    {
        if ($value === null || $value === "") {
            return null;
        }

        $value = str_replace(",", ".", trim((string) $value));

        if (!is_numeric($value)) {
            throw new TransformationFailedException("Le montant doit être un nombre valide.");
        }

        return (int) round((float) $value * 100);
    }
}
