<?php

namespace Martijnvdb\TypeVault\Types;

/**
 * @property string $value
 */
abstract class BaseString extends Type
{
    public function __construct(string | null $value, TypeOptions | null $options = null)
    {
        parent::__construct($value, $options);
    }

    protected function validate(mixed $value): bool
    {
        return is_string($value);
    }
}
