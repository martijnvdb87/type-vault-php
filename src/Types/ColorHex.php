<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\ColorHexValuesDTO;

class ColorHex extends Color
{
    public int $red {
        get => $this->hexToNumberValues($this->value)->red;

        set(int $value) {
            $this->assertMutable();

            $values = $this->hexToNumberValues($this->value);

            $this->value = $values->copyWith(['red' => $value])->__toString();
        }
    }

    public int $green {
        get => $this->hexToNumberValues($this->value)->green;

        set(int $value) {
            $this->assertMutable();

            $values = $this->hexToNumberValues($this->value);

            $this->value = $values->copyWith(['green' => $value])->__toString();
        }
    }

    public int $blue {
        get => $this->hexToNumberValues($this->value)->blue;

        set(int $value) {
            $this->assertMutable();

            $values = $this->hexToNumberValues($this->value);

            $this->value = $values->copyWith(['blue' => $value])->__toString();
        }
    }

    public int $alpha {
        get => $this->hexToNumberValues($this->value)->alpha;

        set(int $value) {
            $this->assertMutable();

            $values = $this->hexToNumberValues($this->value);

            $this->value = $values->copyWith(['alpha' => $value])->__toString();
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

    private function hexToNumber(string $hex): int
    {
        $hex = $hex === '' ? '00' : $hex;

        return intval($hex, 16) % 256;
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
