<?php

namespace Martijnvdb\TypeVault\DTOs;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

final readonly class ColorHexValuesDTO extends DTO
{
    public function __construct(
        public float $red,
        public float $green,
        public float $blue,
        public float $alpha
    ) {
        foreach ([$red, $green, $blue, $alpha] as $value) {
            if ($value < 0 || $value > 255) {
                throw new TypeVaultValidationError(
                    "Value must be between 0 and 255"
                );
            }
        }
    }
}
