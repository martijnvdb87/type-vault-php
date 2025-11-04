<?php

namespace Martijnvdb\TypeVault\Utils;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

function assertClamp(string $name, int|float $value, int|float $min, int|float $max): void
{
    if ($value < $min || $value > $max) {
        throw new TypeVaultValidationError(
            "Value '{$name}' must be between {$min} and {$max}"
        );
    }
}
