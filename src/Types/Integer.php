<?php

namespace Martijnvdb\TypeVault\Types;

class Integer extends BaseNumber
{
    protected function modifier(mixed $value): int
    {
        return intval($value);
    }
}
