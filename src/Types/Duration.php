<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\DurationValuesDTO;

class Duration extends Color
{
    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return $this->isValidFormat(strval($value));
    }

    protected function modifier(mixed $value): string
    {
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
}
