<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\ColorOklchValuesDTO;

use function Martijnvdb\TypeVault\Utils\assertClamp;

class ColorOklch extends Color
{
    public float $lightness {
        get => $this->matchValueFormat($this->value)->lightness;

        set(float $value) {
            $this->assertMutable();

            $values = $this->matchValueFormat($this->value);

            $this->value = $values->copyWith(['lightness' => $value])->__toString();
        }
    }

    public float $chroma {
        get => $this->matchValueFormat($this->value)->chroma;

        set(float $value) {
            $this->assertMutable();

            $values = $this->matchValueFormat($this->value);

            $this->value = $values->copyWith(['chroma' => $value])->__toString();
        }
    }

    public float $hue {
        get => $this->matchValueFormat($this->value)->hue;

        set(float $value) {
            $this->assertMutable();

            $values = $this->matchValueFormat($this->value);

            $this->value = $values->copyWith(['hue' => $value])->__toString();
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

        return $this->getMatchFromString($value) !== null;
    }

    protected function modifier(mixed $value): string
    {
        $matchAbsoluteFormat = $this->matchAbsoluteFormat($value);

        if ($matchAbsoluteFormat !== null) {
            return $matchAbsoluteFormat->__toString();
        }

        return $value;
    }

    private function matchAbsoluteFormat(string $value): ColorOklchValuesDTO | null
    {
        $matches = $this->getMatchFromString($value);

        if (!$matches) {
            return null;
        }

        $lightness = (function () use ($matches) {
            $float = floatval($matches[1]);

            if ($matches[2] === '%') {
                return $float;
            }

            return $float * 100;
        })();

        assertClamp(name: "lightness", value: $lightness, min: 0, max: 100);

        $chroma = (function () use ($matches) {
            $float = floatval($matches[3]);

            if ($matches[4] === '%') {
                return ($float / 100) * 0.4;
            }

            return $float;
        })();

        assertClamp(name: "chroma", value: $chroma, min: 0, max: 1);

        $hue = floatval($matches[5]);

        assertClamp(name: "hue", value: $hue, min: 0, max: 360);

        $alpha = (function () use ($matches) {
            if (!isset($matches[6])) {
                return 100;
            }

            $float = floatval($matches[6]);

            if (isset($matches[7]) && $matches[7] === '%') {
                return $float;
            }

            return $float * 100;
        })();

        assertClamp(name: "alpha", value: $alpha, min: 0, max: 100);

        return new ColorOklchValuesDTO(
            lightness: $lightness,
            chroma: $chroma,
            hue: $hue,
            alpha: $alpha,
        );
    }

    private function matchValueFormat(string | null $value): ColorOklchValuesDTO
    {
        $matches = $this->getMatchFromString($value ?? '');

        if (!$matches) {
            return new ColorOklchValuesDTO(
                lightness: 0,
                chroma: 0,
                hue: 0,
                alpha: 100,
            );
        }

        return new ColorOklchValuesDTO(
            lightness: floatval($matches[1]),
            chroma: floatval($matches[3]),
            hue: floatval($matches[5]),
            alpha: floatval($matches[6]),
        );
    }

    /**
     * @return string[]|null
     */
    private function getMatchFromString(string $value): array | null
    {
        preg_match(' /^oklch\(((?:\d+?)|(?:(?:\d+?)?\.\d+?))(%)? ((?:\d+?)|(?:(?:\d+?)?\.\d+?))(%)? ((?:\d+?)|(?:(?:\d+?)?\.\d+?))(?:deg)?(?: ?\/ ?((?:\d+?)?(?:\.\d+?)?)(%)?)?\)$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return $matches;
    }
}
