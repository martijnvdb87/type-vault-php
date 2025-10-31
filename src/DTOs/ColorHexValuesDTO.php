<?php

namespace Martijnvdb\TypeVault\DTOs;

final readonly class ColorHexValuesDTO extends DTO
{
    public function __construct(
        public float $red,
        public float $green,
        public float $blue,
        public float $alpha
    ) {
        //
    }
}
