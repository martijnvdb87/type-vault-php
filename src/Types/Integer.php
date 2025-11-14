<?php

namespace Martijnvdb\TypeVault\Types;

class Integer extends BaseNumber
{
    protected function modifier(mixed $value): mixed
    {
        $value = parent::modifier($value);

        if (!is_numeric($value)) {
            return $value;
        }

        return intval($value);
    }
}
