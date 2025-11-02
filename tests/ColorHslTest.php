<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\ColorHsl;
use PHPUnit\Framework\TestCase;

class ColorHslTest extends TestCase
{
    /** @var array<int, array<string, float|int|string>> */
    private array $values = [
        [
            'input' => 'hsl(0 0 0 / 0)',
            'output' => 'hsl(0deg 0% 0% / 0%)',
            'hue' => 0,
            'saturation' => 0,
            'lightness' => 0,
            'alpha' => 0,
        ],
        [
            'input' => 'hsl(0deg 0% 0% / 0%)',
            'output' => 'hsl(0deg 0% 0% / 0%)',
            'hue' => 0,
            'saturation' => 0,
            'lightness' => 0,
            'alpha' => 0,
        ],
        [
            'input' => 'hsl(1.23 4.56 7.89 / 3.45%)',
            'output' => 'hsl(1.23deg 4.56% 7.89% / 3.45%)',
            'hue' => 1.23,
            'saturation' => 4.56,
            'lightness' => 7.89,
            'alpha' => 3.45,
        ],
        [
            'input' => 'hsla(360deg 100% 100% / 100%)',
            'output' => 'hsl(360deg 100% 100% / 100%)',
            'hue' => 360,
            'saturation' => 100,
            'lightness' => 100,
            'alpha' => 100,
        ],
        [
            'input' => 'hsl(0, 0, 0, 0)',
            'output' => 'hsl(0deg 0% 0% / 0%)',
            'hue' => 0,
            'saturation' => 0,
            'lightness' => 0,
            'alpha' => 0,
        ],
        [
            'input' => 'hsl(0, 0, 0)',
            'output' => 'hsl(0deg 0% 0% / 100%)',
            'hue' => 0,
            'saturation' => 0,
            'lightness' => 0,
            'alpha' => 100,
        ],
        [
            'input' => 'hsl(0, 0, 0, 1)',
            'output' => 'hsl(0deg 0% 0% / 100%)',
            'hue' => 0,
            'saturation' => 0,
            'lightness' => 0,
            'alpha' => 100,
        ],
        [
            'input' => 'hsl(0, 0, 0, 0.5)',
            'output' => 'hsl(0deg 0% 0% / 50%)',
            'hue' => 0,
            'saturation' => 0,
            'lightness' => 0,
            'alpha' => 50,
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $color = new ColorHsl($value['input']);
            $this->assertEquals($value['output'], $color->value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsInvalid(): void
    {
        $values = [
            'foo',
            'foo@example',
            'foo@example.',
            1,
            [],
            true,
            false,
            null,
        ];

        $this->expectException(TypeVaultValidationError::class);

        foreach ($values as $value) {
            new ColorHsl($value);
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $color = new ColorHsl(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($color->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new ColorHsl(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $color = new ColorHsl('hsl(0 0 0 / 0)', new TypeOptionsDTO(immutable: false));

        $newColorHsl = 'hsl(0deg 0% 0% / 100%)';

        $color->value = $newColorHsl;
        $this->assertEquals($newColorHsl, $color->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $color = new ColorHsl('hsl(0 0 0 / 0)', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $color->value = 'hsl(0 0 0 / 100)';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $color = ColorHsl::nullable(null);
        $this->assertNull($color->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        ColorHsl::immutable('hsl(0 0 0 / 0)')->value = 'hsl(0 0 0 / 100)';
    }
}
