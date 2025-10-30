<?php

namespace Martijnvdb\TypeVault\DTOs;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

readonly class DTO
{
    /**
     * @param array<string, mixed> $overrides
     */
    public function copyWith(array $overrides): static
    {
        $clone = clone $this;

        foreach ($overrides as $property => $value) {
            if (!property_exists($this, $property)) {
                throw new TypeVaultValidationError("Property '{$property}' does not exist in " . static::class);
            }

            $clone->$property = $value;
        }

        return $clone;
    }
}
