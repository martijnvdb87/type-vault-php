<?php

namespace Martijnvdb\TypeVault\DTOs;

use function Martijnvdb\TypeVault\Utils\assertClamp;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

final readonly class TimeOnlyValuesDTO extends DTO
{
    public function __construct(
        public int $hour = 0,
        public int $minute = 0,
        public int $second = 0,
        public int $microsecond = 0
    ) {
        assertClamp(name: "hour", value: $hour, min: 0, max: 23);
        assertClamp(name: "minute", value: $minute, min: 0, max: 59);
        assertClamp(name: "second", value: $second, min: 0, max: 59);
        assertClamp(name: "microsecond", value: $microsecond, min: 0, max: 999);

        $value = $this->__toString();

        $dateTime = new \DateTime("0000-01-01T{$value}Z");

        $microsecondString = substr($dateTime->format('u'), 0, 3);
        $timeOnlyString = $dateTime->format('H:i:s') . '.' . $microsecondString;

        if (strcmp($value, $timeOnlyString) < 0) {
            throw new TypeVaultValidationError("Invalid time only");
        }
    }

    public function __toString(): string
    {
        $hour = str_pad(strval($this->hour), 2, '0', STR_PAD_LEFT);
        $minute = str_pad(strval($this->minute), 2, '0', STR_PAD_LEFT);
        $second = str_pad(strval($this->second), 2, '0', STR_PAD_LEFT);
        $microsecond = str_pad(strval($this->microsecond), 3, '0', STR_PAD_LEFT);

        return "{$hour}:{$minute}:{$second}.{$microsecond}";
    }
}
