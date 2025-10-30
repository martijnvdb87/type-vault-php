<?php

namespace Martijnvdb\TypeVault\Utils;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

function assertClamp(float $value, float $min, float $max): float
{
    if ($value < $min || $value > $max) {
        throw new TypeVaultValidationError(
            "Value must be between {$min} and {$max}"
        );
    }

    return $value;
}
