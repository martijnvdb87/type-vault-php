<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\ColorHsl;
use PHPUnit\Framework\Attributes\DataProviderExternal;
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

    public function testItShouldReturnTheCorrectColorValues(): void
    {
        foreach ($this->values as $value) {
            $color = new ColorHsl($value['input']);
            $this->assertEquals($value['hue'], $color->hue);
            $this->assertEquals($value['saturation'], $color->saturation);
            $this->assertEquals($value['lightness'], $color->lightness);
            $this->assertEquals($value['alpha'], $color->alpha);
        }
    }

    public function testItCanUpdateTheColorValues(): void
    {
        foreach ($this->values as $value) {
            $color = ColorHsl::nullable();

            $color->hue = $value['hue'];
            $color->saturation = $value['saturation'];
            $color->lightness = $value['lightness'];
            $color->alpha = $value['alpha'];

            $this->assertEquals($value['output'], $color->value);
        }
    }

    public function testItCanModifyColorValues(): void
    {
        $color = new ColorHsl('hsl(0, 0, 0)');

        $color->hue = 360;
        $color->saturation = 100;
        $color->lightness = 100;
        $color->alpha = 100;

        $this->assertEquals('hsl(360deg 100% 100% / 100%)', $color->value);

        $color->hue = 0;
        $color->saturation = 0;
        $color->lightness = 0;
        $color->alpha = 0;

        $this->assertEquals('hsl(0deg 0% 0% / 0%)', $color->value);

        $color->hue = 180;
        $color->saturation = 50;
        $color->lightness = 50;
        $color->alpha = 50;

        $this->assertEquals('hsl(180deg 50% 50% / 50%)', $color->value);

        $color->hue = 25;
        $color->saturation = 50;
        $color->lightness = 100;
        $color->alpha = 10;

        $this->assertEquals('hsl(25deg 50% 100% / 10%)', $color->value);

        $color->hue = 12.5;
        $color->saturation = 45.6;
        $color->lightness = 7.89;
        $color->alpha = 1.23;

        $this->assertEquals('hsl(12.5deg 45.6% 7.89% / 1.23%)', $color->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $color = new ColorHsl('hsl(0deg, 0%, 0%, 0)');

        try {
            $color->hue = 361;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->saturation = 101;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->lightness = 101;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->alpha = 101;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->hue = -1;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->saturation = -1;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->lightness = -1;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->alpha = -1;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function invalidDataSet(): array
    {
        return [
            ['foo'],
            ['#foo'],
            ['foo@example'],
            ['foo@example.'],
            [1],
            [[]],
            [true],
            [false],
            [null],
        ];
    }

    #[DataProviderExternal(self::class, 'invalidDataSet')]
    public function testItShouldThrowExceptionWhenValueIsInvalid(mixed $value): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new ColorHsl($value);
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
        $color = ColorHsl::nullable();
        $this->assertNull($color->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        ColorHsl::immutable('hsl(0 0 0 / 0)')->value = 'hsl(0 0 0 / 100)';
    }
}
