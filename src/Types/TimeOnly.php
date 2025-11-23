<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\TimeOnlyValuesDTO;

class TimeOnly extends BaseString
{
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

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        if (!$this->isValidFormat($value)) {
            return false;
        }

        $components = $this->getComponents($value);

        if (!$components) {
            return false;
        }

        (new TimeOnlyValuesDTO(
            hour: $components["hour"],
            minute: $components["minute"],
            second: $components["second"],
            microsecond: $components["microsecond"]
        ));

        return true;
    }

    protected function modifier(mixed $value): string
    {
        $value = parent::modifier($value);

        $components = $this->getComponents($value);

        if ($components) {
            return (new TimeOnlyValuesDTO(
                hour: $components["hour"],
                minute: $components["minute"],
                second: $components["second"],
                microsecond: $components["microsecond"]
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

        $hour = $matches[1];
        $minute = $matches[2];
        $second = $matches[3];
        $microsecond = str_pad($matches[4] ?? "000", 3, '0');

        return [
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
        preg_match('/^(\d{1,2}):(\d{1,2}):(\d{1,2})(?:.(\d{1,3}))?$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return $matches;
    }

    private function valueToDTO(string|null $value): TimeOnlyValuesDTO
    {
        if ($value === null) {
            return new TimeOnlyValuesDTO();
        }

        $matches = $this->getComponents($value);

        if (!$matches) {
            return new TimeOnlyValuesDTO();
        }

        return (new TimeOnlyValuesDTO(
            hour: $matches["hour"],
            minute: $matches["minute"],
            second: $matches["second"],
            microsecond: $matches["microsecond"]
        ));
    }
}
