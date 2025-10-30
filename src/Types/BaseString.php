<?php

namespace Martijnvdb\TypeVault\Types;

/**
 * @property string|null $value
 */
abstract class BaseString extends Type
{
    protected function validate(mixed $value): bool
    {
        return is_string($value);
    }
}
