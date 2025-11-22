<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\DateTimeValuesDTO;

class DateTime extends BaseString
{
    public int $year {
        get => $this->valueToDTO($this->value)->year;

        set(int $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['year' => $value])->__toString();
        }
    }

    public int $month {
        get => $this->valueToDTO($this->value)->month;

        set(int $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['month' => $value])->__toString();
        }
    }

    public int $day {
        get => $this->valueToDTO($this->value)->day;

        set(int $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['day' => $value])->__toString();
        }
    }

    public int $hour {
        get => $this->valueToDTO($this->value)->hour;

        set(int $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['hour' => $value])->__toString();
        }
    }

    public int $minute {
        get => $this->valueToDTO($this->value)->minute;

        set(int $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['minute' => $value])->__toString();
        }
    }

    public int $second {
        get => $this->valueToDTO($this->value)->second;

        set(int $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['second' => $value])->__toString();
        }
    }

    public int $microsecond {
        get => $this->valueToDTO($this->value)->microsecond;

        set(int $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['microsecond' => $value])->__toString();
        }
    }

    public static function fromDateTime(\DateTime $dateTime): self
    {
        $microsecondString = substr($dateTime->format('u'), 0, 3);
        $dateTimeString = $dateTime->format('Y-m-d\TH:i:s') . '.' . $microsecondString . 'Z';

        return new self($dateTimeString);
    }

    public function toDateTime(): \DateTime | null
    {
        return isset($this->value) ? new \DateTime($this->value) : null;
    }

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return $this->isValidFormat(strval($value));
    }

    protected function modifier(mixed $value): string
    {
        $value = parent::modifier($value);

        $matches = $this->getComponents(strval($value));

        if ($matches) {
            return (new DateTimeValuesDTO(
                year: $matches["year"],
                month: $matches["month"],
                day: $matches["day"],
                hour: $matches["hour"],
                minute: $matches["minute"],
                second: $matches["second"],
                microsecond: $matches["microsecond"]
            ))->__toString();
        }

        return $value;
    }

    /**
     * @return int[]|null
     */
    private function getComponents(string $value): array | null
    {
        $matches = $this->matchPattern($value);

        if ($matches === null) {
            return null;
        }

        $year = $matches[1];
        $month = $matches[2];
        $day = $matches[3];
        $hour = $matches[4];
        $minute = $matches[5];
        $second = $matches[6];
        $microsecond = str_pad($matches[7] ?? "000", 3, '0');

        return [
            "year" => $year ? intval($year) : 0,
            "month" => $month ? intval($month) : 0,
            "day" => $day ? intval($day) : 0,
            "hour" => $hour ? intval($hour) : 0,
            "minute" => $minute ? intval($minute) : 0,
            "second" => $second ? intval($second) : 0,
            "microsecond" => $microsecond ? intval($microsecond) : 0
        ];
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
        preg_match('/^(\d{1,4})-(\d{1,2})-(\d{1,2})T(\d{1,2}):(\d{1,2}):(\d{1,2})(?:.(\d{1,3}))?Z$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return $matches;
    }

    private function valueToDTO(string|null $value): DateTimeValuesDTO
    {
        if ($value === null) {
            return new DateTimeValuesDTO();
        }

        $matches = $this->getComponents($value);

        if (!$matches) {
            return new DateTimeValuesDTO();
        }

        return (new DateTimeValuesDTO(
            year: $matches["year"],
            month: $matches["month"],
            day: $matches["day"],
            hour: $matches["hour"],
            minute: $matches["minute"],
            second: $matches["second"],
            microsecond: $matches["microsecond"]
        ));
    }
}
