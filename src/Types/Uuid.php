<?php

namespace Martijnvdb\TypeVault\Types;

class Uuid extends BaseString
{
    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9][0-9a-f]{3}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $value
        ) === 1;
    }

    public static function nil(): static
    {
        return new static('00000000-0000-0000-0000-000000000000');
    }

    public static function random(): static
    {
        $data = random_bytes(16);
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        $value = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

        return new static($value);
    }
}
