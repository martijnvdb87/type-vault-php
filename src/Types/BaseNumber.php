<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

/**
 * @property int|float|null $value
 */
abstract class BaseNumber extends Type
{
    protected function modifier(mixed $value): mixed
    {
        $value = parent::modifier($value);

        if (!is_numeric($value)) {
            throw new TypeVaultValidationError();
        }

        return $value;
    }

    protected function validate(mixed $value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        if (!is_finite($value)) {
            return false;
        }

        if ($value < $this->min()) {
            return false;
        }

        if ($value > $this->max()) {
            return false;
        }

        return true;
    }

    protected function min(): int
    {
        return PHP_INT_MIN;
    }

    protected function max(): int
    {
        return PHP_INT_MAX;
    }
}
