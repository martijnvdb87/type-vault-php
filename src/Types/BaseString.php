<?php

namespace Martijnvdb\TypeVault\Types;

/**
 * @property string $value
 */
abstract class BaseString extends Type
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }

    protected function validate(mixed $value): bool
    {
        return is_string($value);
    }
}
