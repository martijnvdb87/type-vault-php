<?php

namespace Martijnvdb\TypeVault\DTOs;

use function Martijnvdb\TypeVault\Utils\assertClamp;

final readonly class ColorRgbValuesDTO extends DTO
{
    public function __construct(
        public float $red = 0,
        public float $green = 0,
        public float $blue = 0,
        public float $alpha = 100
    ) {
        assertClamp(name: "red", value: $red, min: 0, max: 255);
        assertClamp(name: "green", value: $green, min: 0, max: 255);
        assertClamp(name: "blue", value: $blue, min: 0, max: 255);
        assertClamp(name: "alpha", value: $alpha, min: 0, max: 100);
    }

    public function __toString(): string
    {
        return "rgb({$this->red} {$this->green} {$this->blue} / {$this->alpha}%)";
    }
}
