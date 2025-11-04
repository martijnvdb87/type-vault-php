<?php

namespace Martijnvdb\TypeVault\DTOs;

use function Martijnvdb\TypeVault\Utils\assertClamp;

final readonly class ColorHexValuesDTO extends DTO
{
    public function __construct(
        public int $red = 0,
        public int $green = 0,
        public int $blue = 0,
        public int $alpha = 255
    ) {
        assertClamp(name: "red", value: $red, min: 0, max: 255);
        assertClamp(name: "green", value: $green, min: 0, max: 255);
        assertClamp(name: "blue", value: $blue, min: 0, max: 255);
        assertClamp(name: "alpha", value: $alpha, min: 0, max: 255);
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
