<?php

namespace Martijnvdb\TypeVault\DTOs;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

final readonly class ColorHslValuesDTO extends DTO
{
    public function __construct(
        public float $hue = 0,
        public float $saturation = 0,
        public float $lightness = 0,
        public float $alpha = 100
    ) {
        if ($hue < 0 || $hue > 360) {
            throw new TypeVaultValidationError(
                "Value must be between 0 and 360"
            );
        }

        foreach ([$saturation, $lightness, $alpha] as $value) {
            if ($value < 0 || $value > 100) {
                throw new TypeVaultValidationError(
                    "Value must be between 0 and 100"
                );
            }
        }
    }

    public function __toString(): string
    {
        return "hsl({$this->hue}deg {$this->saturation}% {$this->lightness}% / {$this->alpha}%)";
    }
}
