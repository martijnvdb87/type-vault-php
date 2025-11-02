<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\ColorHslValuesDTO;

class ColorHsl extends Color
{
    public float $hue {
        get => $this->matchValueFormat($this->value)->hue;

        set(float $value) {
            $this->assertMutable();

            $values = $this->matchValueFormat($this->value);

            $this->value = $values->copyWith(['hue' => $value])->__toString();
        }
    }

    public float $saturation {
        get => $this->matchValueFormat($this->value)->saturation;

        set(float $value) {
            $this->assertMutable();

            $values = $this->matchValueFormat($this->value);

            $this->value = $values->copyWith(['saturation' => $value])->__toString();
        }
    }

    public float $lightness {
        get => $this->matchValueFormat($this->value)->lightness;

        set(float $value) {
            $this->assertMutable();

            $values = $this->matchValueFormat($this->value);

            $this->value = $values->copyWith(['lightness' => $value])->__toString();
        }
    }

    public float $alpha {
        get => $this->matchValueFormat($this->value)->alpha;

        set(float $value) {
            $this->assertMutable();

            $values = $this->matchValueFormat($this->value);

            $this->value = $values->copyWith(['alpha' => $value])->__toString();
        }
    }

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return $this->matchAbsoluteFormat($value) !== null;
    }

    protected function modifier(mixed $value): string
    {
        $matchAbsoluteFormat = $this->matchAbsoluteFormat($value);

        if ($matchAbsoluteFormat !== null) {
            return $matchAbsoluteFormat->__toString();
        }

        $matchLegacyFormat = $this->matchLegacyFormat($value);

        if ($matchLegacyFormat !== null) {
            return $matchLegacyFormat->__toString();
        }

        $matchHslaLegacyFormat = $this->matchHslaLegacyFormat($value);

        if ($matchHslaLegacyFormat !== null) {
            return $matchHslaLegacyFormat->__toString();
        }

        return $value;
    }

    private function matchValueFormat(string | null $value): ColorHslValuesDTO
    {
        $match = $this->matchAbsoluteFormat($value ?? '');

        if ($match === null || $value === null) {
            return new ColorHslValuesDTO(hue: 0, saturation: 0, lightness: 0, alpha: 0);
        }

        return $match;
    }

    private function matchAbsoluteFormat(string $value): ColorHslValuesDTO | null
    {
        preg_match('/^hsl\((\d+?(?:\.\d+?)?)(?:deg)? (\d+?(?:\.\d+?)?)%? (\d+?(?:\.\d+?)?)%?(?: ?\/ ?(\d+?(?:\.\d+?)?)%?)?\)$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return new ColorHslValuesDTO(
            hue: floatval($matches[1]),
            saturation: floatval($matches[2]),
            lightness: floatval($matches[3]),
            alpha: floatval($matches[4] ?? 100),
        );
    }

    private function matchLegacyFormat(string $value): ColorHslValuesDTO | null
    {
        preg_match('/^hsla?\((\d+?(?:\.\d+?)?)(?:deg)?, ?(\d+?(?:\.\d+?)?)%?, ?(\d+?(?:\.\d+?)?)%?(?:, ?(\d+?(?:\.\d+?)?))?\)$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        $alpha = !isset($matches[4]) ? 100 : floatval($matches[4]) * 100;

        return new ColorHslValuesDTO(
            hue: floatval($matches[1]),
            saturation: floatval($matches[2]),
            lightness: floatval($matches[3]),
            alpha: floatval($alpha)
        );
    }

    private function matchHslaLegacyFormat(string $value): ColorHslValuesDTO | null
    {
        preg_match('/^hsla\((\d+?(?:\.\d+?)?)(?:deg)? (\d+?(?:\.\d+?)?)%? (\d+?(?:\.\d+?)?)%?(?: ?\/ ?(\d+?(?:\.\d+?)?)%?)?\)$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return new ColorHslValuesDTO(
            hue: floatval($matches[1]),
            saturation: floatval($matches[2]),
            lightness: floatval($matches[3]),
            alpha: floatval($matches[4] ?? 100),
        );
    }
}
