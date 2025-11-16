<?php

namespace Martijnvdb\TypeVault\Types;

class Year extends BaseNumber
{
    protected function min(): int
    {
        return 0;
    }

    protected function max(): int
    {
        return 9999;
    }

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        if ($value !== intval($value)) {
            return false;
        }

        return true;
    }
}
