<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

/**
 * @property string|null $value
 */
abstract class BaseString extends Type
{
    protected function modifier(mixed $value): string
    {
        $value = parent::modifier($value);

        if (!is_string($value)) {
            throw new TypeVaultValidationError();
        }

        return $value;
    }

    protected function validate(mixed $value): bool
    {
        return is_string($value);
    }
}
