<?php

namespace Martijnvdb\TypeVault\DTOs;

readonly class TypeOptionsDTO extends DTO
{
    public function __construct(
        public bool $nullable = false,
        public bool $immutable = false
    ) {
        //
    }
}
