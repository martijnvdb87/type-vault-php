<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\ColorOklch;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class ColorOklchTest extends TestCase
{
    /** @var array<int, array<string, float|int|string>> */
    private array $values = [
        [
            'input'  => 'oklch(0.7 0.2 120)',
            'output'  => 'oklch(70% 0.2 120deg / 100%)',
            'lightness'  => 70,
            'chroma'  => 0.2,
            'hue'  => 120,
            'alpha'  => 100,
        ],
        [
            'input'  => 'oklch(0.7 0.2 120 / 50%)',
            'output'  => 'oklch(70% 0.2 120deg / 50%)',
            'lightness'  => 70,
            'chroma'  => 0.2,
            'hue'  => 120,
            'alpha'  => 50,
        ],
        [
            'input'  => 'oklch(70% 100% 120deg / 0.25)',
            'output'  => 'oklch(70% 0.4 120deg / 25%)',
            'lightness'  => 70,
            'chroma'  => 0.4,
            'hue'  => 120,
            'alpha'  => 25,
        ],
        [
            'input'  => 'oklch(.1% .1% .1deg / .1%)',
            'output'  => 'oklch(0.1% 0.0004 0.1deg / 0.1%)',
            'lightness'  => 0.1,
            'chroma'  => 0.0004,
            'hue'  => 0.1,
            'alpha'  => 0.1,
        ],
        [
            'input'  => 'oklch(.1 .1 .1 / .1)',
            'output'  => 'oklch(10% 0.1 0.1deg / 10%)',
            'lightness'  => 10,
            'chroma'  => 0.1,
            'hue'  => 0.1,
            'alpha'  => 10,
        ],
        [
            'input'  => 'oklch(100% 100% 360deg / 100%)',
            'output'  => 'oklch(100% 0.4 360deg / 100%)',
            'lightness'  => 100,
            'chroma'  => 0.4,
            'hue'  => 360,
            'alpha'  => 100,
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $instance = new ColorOklch($value['input']);
            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItShouldReturnTheCorrectColorValues(): void
    {
        foreach ($this->values as $value) {
            $instance = new ColorOklch($value['input']);
            $this->assertEquals($value['lightness'], $instance->lightness);
            $this->assertEquals($value['chroma'], $instance->chroma);
            $this->assertEquals($value['hue'], $instance->hue);
            $this->assertEquals($value['alpha'], $instance->alpha);
        }
    }

    public function testItCanUpdateTheColorValues(): void
    {
        foreach ($this->values as $value) {
            $instance = ColorOklch::nullable();

            $instance->lightness = $value['lightness'];
            $instance->chroma = $value['chroma'];
            $instance->hue = $value['hue'];
            $instance->alpha = $value['alpha'];

            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItCanModifyPropertyValues(): void
    {
        $instance = new ColorOklch('oklch(0 0 0)');

        $instance->lightness = 100;
        $instance->chroma = 0.4;
        $instance->hue = 100;
        $instance->alpha = 100;

        $this->assertEquals('oklch(100% 0.4 100deg / 100%)', $instance->value);

        $instance->lightness = 0;
        $instance->chroma = 0;
        $instance->hue = 0;
        $instance->alpha = 0;

        $this->assertEquals('oklch(0% 0 0deg / 0%)', $instance->value);

        $instance->lightness = 50;
        $instance->chroma = 0.2;
        $instance->hue = 180;
        $instance->alpha = 50;

        $this->assertEquals('oklch(50% 0.2 180deg / 50%)', $instance->value);

        $instance->lightness = 12.5;
        $instance->chroma = 0.125;
        $instance->hue = 37.5;
        $instance->alpha = 12.34;

        $this->assertEquals('oklch(12.5% 0.125 37.5deg / 12.34%)', $instance->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $instance = new ColorOklch('oklch(0 0 0)');

        try {
            $instance->lightness = 101;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->chroma = 2;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->hue = 361;
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
            $instance->lightness = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->chroma = -1;
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
        new ColorOklch($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $instance = new ColorOklch(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new ColorOklch(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $instance = new ColorOklch('oklch(0% 0 0deg / 0%)', new TypeOptionsDTO(immutable: false));

        $newInstance = 'oklch(100% 0.4 360deg / 100%)';

        $instance->value = $newInstance;
        $this->assertEquals($newInstance, $instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $instance = new ColorOklch('oklch(0% 0 0deg / 0%)', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $instance->value = 'oklch(100% 0.4 360deg / 100%)';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $instance = ColorOklch::nullable();
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        ColorOklch::immutable('oklch(0% 0 0deg / 0%)')->value = 'oklch(100% 0.4 360deg / 100%)';
    }
}
