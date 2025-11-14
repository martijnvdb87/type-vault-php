<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\ColorRgbValuesDTO;

class ColorRgb extends Color
{
    public float $red {
        get => $this->stringToNumberValues($this->value)->red;

        set(float $value) {
            $this->assertMutable();

            $values = $this->stringToNumberValues($this->value);

            $this->value = $values->copyWith(['red' => $value])->__toString();
        }
    }

    public float $green {
        get => $this->stringToNumberValues($this->value)->green;

        set(float $value) {
            $this->assertMutable();

            $values = $this->stringToNumberValues($this->value);

            $this->value = $values->copyWith(['green' => $value])->__toString();
        }
    }

    public float $blue {
        get => $this->stringToNumberValues($this->value)->blue;

        set(float $value) {
            $this->assertMutable();

            $values = $this->stringToNumberValues($this->value);

            $this->value = $values->copyWith(['blue' => $value])->__toString();
        }
    }

    public float $alpha {
        get => $this->stringToNumberValues($this->value)->alpha;

        set(float $value) {
            $this->assertMutable();

            $values = $this->stringToNumberValues($this->value);

            $this->value = $values->copyWith(['alpha' => $value])->__toString();
        }
    }

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return $this->getMatchFromString($value) !== null;
    }

    protected function modifier(mixed $value): string
    {
        $value = parent::modifier($value);

        $matches = $this->getMatchFromString($value);

        if ($matches) {
            $alpha = (function () use ($matches) {
                if (!isset($matches[4])) {
                    return (float) 100;
                }

                preg_match('/^((?:\d*)(?:\.\d*)?)%$/', strval($matches[4]), $alphaPercentageMatches);

                if ($alphaPercentageMatches) {
                    return floatval($alphaPercentageMatches[1]);
                }

                return floatval($matches[4]) * 100;
            })();

            $value = new ColorRgbValuesDTO(
                red: floatval($matches[1]),
                green: floatval($matches[2]),
                blue: floatval($matches[3]),
                alpha: $alpha
            )->__toString();
        }

        return $value;
    }

    private function stringToNumberValues(string | null $value): ColorRgbValuesDTO
    {
        $matches = $this->getMatchFromString($value ?? '');

        if (!$matches) {
            return new ColorRgbValuesDTO(
                red: 0,
                green: 0,
                blue: 0,
                alpha: 100
            );
        }

        return new ColorRgbValuesDTO(
            red: floatval($matches[1]),
            green: floatval($matches[2]),
            blue: floatval($matches[3]),
            alpha: floatval($matches[4]),
        );
    }

    /**
     * @return string[]|null
     */
    private function getMatchFromString(string $value): array | null
    {
        preg_match('/^rgba?\((25[0-5]|2[0-4][0-9]|1?[0-9]{1,2}(?:\.\d*)?)(?:, ?| )(25[0-5]|2[0-4][0-9]|1?[0-9]{1,2}(?:\.\d*)?)(?:, ?| )(25[0-5]|2[0-4][0-9]|1?[0-9]{1,2}(?:\.\d*)?)(?:(?:, ?| ?\/ ?)((?:(?:100|(?:\d{1,2})(?:\.\d+)?)%)|(?:1|(?:0(?:\.\d+)?))))?\)$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return $matches;
    }
}
