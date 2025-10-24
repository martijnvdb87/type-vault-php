<?php

namespace Martijnvdb\TypeVault\Types;

class Email extends BaseString
{
    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
