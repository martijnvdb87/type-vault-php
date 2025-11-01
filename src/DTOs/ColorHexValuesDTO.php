<?php

namespace Martijnvdb\TypeVault\DTOs;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

final readonly class ColorHexValuesDTO extends DTO
{
    public function __construct(
        public int $red = 0,
        public int $green = 0,
        public int $blue = 0,
        public int $alpha = 255
    ) {
        foreach ([$red, $green, $blue, $alpha] as $value) {
            if ($value < 0 || $value > 255) {
                throw new TypeVaultValidationError(
                    "Value must be between 0 and 255"
                );
            }
        }
    }

    public function __toString(): string
    {
        return "#{$this->numberToHex($this->red)}{$this->numberToHex($this->green)}{$this->numberToHex($this->blue)}{$this->numberToHex($this->alpha)}";
    }

    private function numberToHex(int $number): string
    {
        $hex = dechex($number % 256);

        return str_pad($hex, 2, '0', STR_PAD_LEFT);
    }
}
