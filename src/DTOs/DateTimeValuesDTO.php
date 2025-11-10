<?php

namespace Martijnvdb\TypeVault\DTOs;

use function Martijnvdb\TypeVault\Utils\assertClamp;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

final readonly class DateTimeValuesDTO extends DTO
{
    public function __construct(
        public int $year = 0,
        public int $month = 1,
        public int $day = 1,
        public int $hour = 0,
        public int $minute = 0,
        public int $second = 0,
        public int $microsecond = 0
    ) {
        assertClamp(name: "year", value: $year, min: 0, max: 9999);
        assertClamp(name: "month", value: $month, min: 1, max: 12);
        assertClamp(name: "day", value: $day, min: 1, max: 31);
        assertClamp(name: "hour", value: $hour, min: 0, max: 23);
        assertClamp(name: "minute", value: $minute, min: 0, max: 59);
        assertClamp(name: "second", value: $second, min: 0, max: 59);
        assertClamp(name: "microsecond", value: $microsecond, min: 0, max: 999);

        $dateTimeString = $this->__toString();

        $dateTime = new \DateTime($dateTimeString);

        $microsecondString = substr($dateTime->format('u'), 0, 3);
        $dateTimeString = $dateTime->format('Y-m-d\TH:i:s') . '.' . $microsecondString . 'Z';

        if (strcmp($dateTimeString, $dateTimeString) < 0) {
            throw new TypeVaultValidationError("Invalid date time");
        }
    }

    public function __toString(): string
    {
        $year = str_pad(strval($this->year), 4, '0', STR_PAD_LEFT);
        $month = str_pad(strval($this->month), 2, '0', STR_PAD_LEFT);
        $day = str_pad(strval($this->day), 2, '0', STR_PAD_LEFT);
        $hour = str_pad(strval($this->hour), 2, '0', STR_PAD_LEFT);
        $minute = str_pad(strval($this->minute), 2, '0', STR_PAD_LEFT);
        $second = str_pad(strval($this->second), 2, '0', STR_PAD_LEFT);
        $microsecond = str_pad(strval($this->microsecond), 3, '0', STR_PAD_LEFT);

        return "{$year}-{$month}-{$day}T{$hour}:{$minute}:{$second}.{$microsecond}Z";
    }
}
