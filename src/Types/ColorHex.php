<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\ColorHexValuesDTO;

use function Martijnvdb\TypeVault\Utils\assertClamp;

class ColorHex extends Color
{
    public float $red {
        get => $this->hexToNumberValues($this->value)->red;

        set(mixed $value) {
            $this->assertMutable();

            $values = $this->hexToNumberValues($this->value);

            $this->value = $this->numberToHexString($values->copyWith(['red' => $value]));
        }
    }

    public float $green {
        get => $this->hexToNumberValues($this->value)->green;

        set(mixed $value) {
            $this->assertMutable();

            $values = $this->hexToNumberValues($this->value);

            $this->value = $this->numberToHexString($values->copyWith(['green' => $value]));
        }
    }

    public float $blue {
        get => $this->hexToNumberValues($this->value)->blue;

        set(mixed $value) {
            $this->assertMutable();

            $values = $this->hexToNumberValues($this->value);

            $this->value = $this->numberToHexString($values->copyWith(['blue' => $value]));
        }
    }

    public float $alpha {
        get => $this->hexToNumberValues($this->value)->alpha;

        set(mixed $value) {
            $this->assertMutable();

            $values = $this->hexToNumberValues($this->value);

            $this->value = $this->numberToHexString($values->copyWith(['alpha' => $value]));
        }
    }

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return preg_match(
            '/^#[0-9a-fA-F]{8}$/',
            $value
        ) === 1;
    }

    protected function modifier(mixed $value): string
    {
        preg_match('/^#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])$/', strval($value), $shortNotation);

        if ($shortNotation) {
            $parts = implode('', [
                $shortNotation[1],
                $shortNotation[1],
                $shortNotation[2],
                $shortNotation[2],
                $shortNotation[3],
                $shortNotation[3],
                'ff',
            ]);

            return strtolower("#{$parts}");
        }

        preg_match('/^#([0-9a-fA-F]{6,8})$/', strval($value), $regularNotation);

        if ($regularNotation) {
            return str_pad(strtolower($regularNotation[0]), 9, 'f');
        }

        return $value;
    }

    private function hexToNumber(string $hex): float
    {
        $hex = $hex === '' ? '00' : $hex;

        return intval($hex, 16) % 256;
    }

    private function numberToHex(float $number): string
    {
        $hex = dechex($number % 256);

        return str_pad($hex, 2, '0', STR_PAD_LEFT);
    }

    private function numberToHexString(ColorHexValuesDTO $values): string
    {
        $parts = [
            $this->numberToHex(assertClamp($values->red, min: 0, max: 255)),
            $this->numberToHex(assertClamp($values->green, min: 0, max: 255)),
            $this->numberToHex(assertClamp($values->blue, min: 0, max: 255)),
            $this->numberToHex(assertClamp($values->alpha, min: 0, max: 255)),
        ];

        $result = implode('', $parts);

        return "#{$result}";
    }

    private function hexToNumberValues(string | null $value): ColorHexValuesDTO
    {
        if ($value === null) {
            return new ColorHexValuesDTO(red: 0, green: 0, blue: 0, alpha: 0);
        }

        return new ColorHexValuesDTO(
            red: $this->hexToNumber(substr($value, 1, 2)),
            green: $this->hexToNumber(substr($value, 3, 2)),
            blue: $this->hexToNumber(substr($value, 5, 2)),
            alpha: $this->hexToNumber(substr($value, 7, 2)),
        );
    }
}
