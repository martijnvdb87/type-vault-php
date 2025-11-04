<?php

namespace Martijnvdb\TypeVault\DTOs;

use function Martijnvdb\TypeVault\Utils\assertClamp;

final readonly class ColorOklchValuesDTO extends DTO
{
    public function __construct(
        public float $lightness = 0,
        public float $chroma = 0,
        public float $hue = 0,
        public float $alpha = 100
    ) {
        assertClamp(name: "lightness", value: $lightness, min: 0, max: 100);
        assertClamp(name: "chroma", value: $chroma, min: 0, max: 1);
        assertClamp(name: "hue", value: $hue, min: 0, max: 360);
        assertClamp(name: "alpha", value: $alpha, min: 0, max: 100);
    }

    public function __toString(): string
    {
        return "oklch({$this->lightness}% {$this->chroma} {$this->hue}deg / {$this->alpha}%)";
    }
}
