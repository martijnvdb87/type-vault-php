<?php

namespace Martijnvdb\TypeVault\DTOs;

use function Martijnvdb\TypeVault\Utils\assertClamp;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

final readonly class DateOnlyValuesDTO extends DTO
{
    public function __construct(
        public int $year = 0,
        public int $month = 1,
        public int $day = 1,
    ) {
        assertClamp(name: "year", value: $year, min: 0, max: 9999);
        assertClamp(name: "month", value: $month, min: 1, max: 12);
        assertClamp(name: "day", value: $day, min: 1, max: 31);

        $dateTimeString = $this->__toString();

        $dateTime = new \DateTime($dateTimeString);

        if ($dateTime->format('Y-m-d') !== $dateTimeString) {
            throw new TypeVaultValidationError("Invalid date");
        }
    }

    public function __toString(): string
    {
        $year = str_pad(strval($this->year), 4, '0', STR_PAD_LEFT);
        $month = str_pad(strval($this->month), 2, '0', STR_PAD_LEFT);
        $day = str_pad(strval($this->day), 2, '0', STR_PAD_LEFT);

        return "{$year}-{$month}-{$day}";
    }
}
