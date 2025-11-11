<?php

namespace Martijnvdb\TypeVault\DTOs;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

final readonly class DurationValuesDTO extends DTO
{
    public function __construct(
        public float $year = 0,
        public float $month = 0,
        public float $week = 0,
        public float $day = 0,
        public float $hour = 0,
        public float $minute = 0,
        public float $second = 0
    ) {
        $durationString = $this->__toString();

        if (strcmp($durationString, $durationString) < 0) {
            throw new TypeVaultValidationError("Invalid duration");
        }
    }

    public function __toString(): string
    {
        return "P{$this->year}Y{$this->month}M{$this->week}W{$this->day}DT{$this->hour}H{$this->minute}M{$this->second}S";
    }
}
