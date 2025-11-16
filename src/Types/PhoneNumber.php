<?php

namespace Martijnvdb\TypeVault\Types;

class PhoneNumber extends BaseString
{
    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return $this->isValidFormat(strval($value));
    }

    private function isValidFormat(string $value): bool
    {
        return $this->matchPattern($value) !== null;
    }

    /**
     * @return string[]|null
     */
    private function matchPattern(string $value): array | null
    {
        preg_match('/^\+[1-9]\d{1,14}$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return $matches;
    }
}
