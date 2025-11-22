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
            $instance = new ColorHsl($value['input']);
            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItShouldReturnTheCorrectColorValues(): void
    {
        foreach ($this->values as $value) {
            $instance = new ColorHsl($value['input']);
            $this->assertEquals($value['hue'], $instance->hue);
            $this->assertEquals($value['saturation'], $instance->saturation);
            $this->assertEquals($value['lightness'], $instance->lightness);
            $this->assertEquals($value['alpha'], $instance->alpha);
        }
    }

    public function testItCanUpdateTheColorValues(): void
    {
        foreach ($this->values as $value) {
            $instance = ColorHsl::nullable();

            $instance->hue = $value['hue'];
            $instance->saturation = $value['saturation'];
            $instance->lightness = $value['lightness'];
            $instance->alpha = $value['alpha'];

            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItCanModifyPropertyValues(): void
    {
        $instance = new ColorHsl('hsl(0, 0, 0)');

        $instance->hue = 360;
        $instance->saturation = 100;
        $instance->lightness = 100;
        $instance->alpha = 100;

        $this->assertEquals('hsl(360deg 100% 100% / 100%)', $instance->value);

        $instance->hue = 0;
        $instance->saturation = 0;
        $instance->lightness = 0;
        $instance->alpha = 0;

        $this->assertEquals('hsl(0deg 0% 0% / 0%)', $instance->value);

        $instance->hue = 180;
        $instance->saturation = 50;
        $instance->lightness = 50;
        $instance->alpha = 50;

        $this->assertEquals('hsl(180deg 50% 50% / 50%)', $instance->value);

        $instance->hue = 25;
        $instance->saturation = 50;
        $instance->lightness = 100;
        $instance->alpha = 10;

        $this->assertEquals('hsl(25deg 50% 100% / 10%)', $instance->value);

        $instance->hue = 12.5;
        $instance->saturation = 45.6;
        $instance->lightness = 7.89;
        $instance->alpha = 1.23;

        $this->assertEquals('hsl(12.5deg 45.6% 7.89% / 1.23%)', $instance->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $instance = new ColorHsl('hsl(0deg, 0%, 0%, 0)');

        try {
            $instance->hue = 361;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->saturation = 101;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->lightness = 101;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->alpha = 101;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->hue = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->saturation = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->lightness = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->alpha = -1;
            $this->fail();
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
        $instance = new ColorHsl(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new ColorHsl(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $instance = new ColorHsl('hsl(0 0 0 / 0)', new TypeOptionsDTO(immutable: false));

        $newInstance = 'hsl(0deg 0% 0% / 100%)';

        $instance->value = $newInstance;
        $this->assertEquals($newInstance, $instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $instance = new ColorHsl('hsl(0 0 0 / 0)', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $instance->value = 'hsl(0 0 0 / 100)';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $instance = ColorHsl::nullable();
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        ColorHsl::immutable('hsl(0 0 0 / 0)')->value = 'hsl(0 0 0 / 100)';
    }
}
