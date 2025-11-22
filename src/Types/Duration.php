<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\DurationValuesDTO;

class Duration extends BaseString
{
    public float $year {
        get => $this->valueToDTO($this->value)->year;

        set(float $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['year' => $value])->__toString();
        }
    }

    public float $month {
        get => $this->valueToDTO($this->value)->month;

        set(float $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['month' => $value])->__toString();
        }
    }

    public float $week {
        get => $this->valueToDTO($this->value)->week;

        set(float $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['week' => $value])->__toString();
        }
    }

    public float $day {
        get => $this->valueToDTO($this->value)->day;

        set(float $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['day' => $value])->__toString();
        }
    }

    public float $hour {
        get => $this->valueToDTO($this->value)->hour;

        set(float $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['hour' => $value])->__toString();
        }
    }

    public float $minute {
        get => $this->valueToDTO($this->value)->minute;

        set(float $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['minute' => $value])->__toString();
        }
    }

    public float $second {
        get => $this->valueToDTO($this->value)->second;

        set(float $value) {
            $this->assertMutable();

            $values = $this->valueToDTO($this->value);

            $this->value = $values->copyWith(['second' => $value])->__toString();
        }
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
            return (new DurationValuesDTO(
                year: $matches["year"],
                month: $matches["month"],
                week: $matches["week"],
                day: $matches["day"],
                hour: $matches["hour"],
                minute: $matches["minute"],
                second: $matches["second"]
            ))->__toString();
        }

        return $value;
    }

    /**
     * @return float[]|null
     */
    private function getComponents(string $value): array | null
    {
        $matches = $this->matchPattern($value);

        if ($matches === null) {
            return null;
        }

        return [
            "year" => isset($matches[1]) ? floatval($matches[1]) : 0,
            "month" => isset($matches[2]) ? floatval($matches[2]) : 0,
            "week" => isset($matches[3]) ? floatval($matches[3]) : 0,
            "day" => isset($matches[4]) ? floatval($matches[4]) : 0,
            "hour" => isset($matches[5]) ? floatval($matches[5]) : 0,
            "minute" => isset($matches[6]) ? floatval($matches[6]) : 0,
            "second" => isset($matches[7]) ? floatval($matches[7]) : 0
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
        preg_match('/^P(?:((?:[1-9]\d*(?:\.\d+)?)|(?:0(?:\.\d+)?))Y)?(?:((?:[1-9]\d*(?:\.\d+)?)|(?:0(?:\.\d+)?))M)?(?:((?:[1-9]\d*(?:\.\d+)?)|(?:0(?:\.\d+)?))W)?(?:((?:[1-9]\d*(?:\.\d+)?)|(?:0(?:\.\d+)?))D)?(?:T(?:((?:[1-9]\d*(?:\.\d+)?)|(?:0(?:\.\d+)?))H)?(?:((?:[1-9]\d*(?:\.\d+)?)|(?:0(?:\.\d+)?))M)?(?:((?:[1-9]\d*(?:\.\d+)?)|(?:0(?:\.\d+)?))S)?)?$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return $matches;
    }

    private function valueToDTO(string|null $value): DurationValuesDTO
    {
        if ($value === null) {
            return new DurationValuesDTO();
        }

        $matches = $this->getComponents($value);

        if (!$matches) {
            return new DurationValuesDTO();
        }

        return (new DurationValuesDTO(
            year: $matches["year"],
            month: $matches["month"],
            week: $matches["week"],
            day: $matches["day"],
            hour: $matches["hour"],
            minute: $matches["minute"],
            second: $matches["second"]
        ));
    }
}
