<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\ColorRgb;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class ColorRgbTest extends TestCase
{
    /** @var array<int, array<string, float|int|string>> */
    private array $values = [
        [
            'input' => 'rgb(0, 0, 0)',
            'output' => 'rgb(0 0 0 / 100%)',
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => 100
        ],
        [
            'input' => 'rgb(255, 255, 255)',
            'output' => 'rgb(255 255 255 / 100%)',
            'red' => 255,
            'green' => 255,
            'blue' => 255,
            'alpha' => 100,
        ],
        [
            'input' => 'rgba(0, 0, 0)',
            'output' => 'rgb(0 0 0 / 100%)',
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => 100
        ],
        [
            'input' => 'rgba(255, 255, 255)',
            'output' => 'rgb(255 255 255 / 100%)',
            'red' => 255,
            'green' => 255,
            'blue' => 255,
            'alpha' => 100,
        ],
        [
            'input' => 'rgba(0, 0, 0)',
            'output' => 'rgb(0 0 0 / 100%)',
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => 100
        ],
        [
            'input' => 'rgba(255, 255, 255)',
            'output' => 'rgb(255 255 255 / 100%)',
            'red' => 255,
            'green' => 255,
            'blue' => 255,
            'alpha' => 100,
        ],
        [
            'input' => 'rgba(0, 0, 0, 1)',
            'output' => 'rgb(0 0 0 / 100%)',
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => 100,
        ],
        [
            'input' => 'rgba(255, 255, 255, 1)',
            'output' => 'rgb(255 255 255 / 100%)',
            'red' => 255,
            'green' => 255,
            'blue' => 255,
            'alpha' => 100,
        ],
        [
            'input' => 'rgba(0, 0, 0, 0.5)',
            'output' => 'rgb(0 0 0 / 50%)',
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => 50,
        ],
        [
            'input' => 'rgba(255, 255, 255, 0.5)',
            'output' => 'rgb(255 255 255 / 50%)',
            'red' => 255,
            'green' => 255,
            'blue' => 255,
            'alpha' => 50,
        ],
        [
            'input' => 'rgba(0, 0, 0, 0.25)',
            'output' => 'rgb(0 0 0 / 25%)',
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => 25,
        ],
        [
            'input' => 'rgba(255, 255, 255, 0.25)',
            'output' => 'rgb(255 255 255 / 25%)',
            'red' => 255,
            'green' => 255,
            'blue' => 255,
            'alpha' => 25,
        ],
        [
            'input' => 'rgba(0 0 0 / 0.125)',
            'output' => 'rgb(0 0 0 / 12.5%)',
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => 12.5,
        ],
        [
            'input' => 'rgba(255 255 255 / 0.125)',
            'output' => 'rgb(255 255 255 / 12.5%)',
            'red' => 255,
            'green' => 255,
            'blue' => 255,
            'alpha' => 12.5,
        ],
        [
            'input' => 'rgb(0 0 0 / 0.125)',
            'output' => 'rgb(0 0 0 / 12.5%)',
            'red' => 0,
            'green' => 0,
            'blue' => 0,
            'alpha' => 12.5,
        ],
        [
            'input' => 'rgb(255 255 255 / 0.125)',
            'output' => 'rgb(255 255 255 / 12.5%)',
            'red' => 255,
            'green' => 255,
            'blue' => 255,
            'alpha' => 12.5,
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $instance = new ColorRgb($value['input']);
            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItShouldReturnTheCorrectColorValues(): void
    {
        foreach ($this->values as $value) {
            $instance = new ColorRgb($value['input']);
            $this->assertEquals($value['red'], $instance->red);
            $this->assertEquals($value['green'], $instance->green);
            $this->assertEquals($value['blue'], $instance->blue);
            $this->assertEquals($value['alpha'], $instance->alpha);
        }
    }

    public function testItCanUpdateTheColorValues(): void
    {
        foreach ($this->values as $value) {
            $instance = ColorRgb::nullable();

            $instance->red = $value['red'];
            $instance->green = $value['green'];
            $instance->blue = $value['blue'];
            $instance->alpha = $value['alpha'];

            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItCanModifyPropertyValues(): void
    {
        $instance = new ColorRgb('rgb(0, 0, 0)');

        $instance->red = 255;
        $instance->green = 255;
        $instance->blue = 255;
        $instance->alpha = 100;

        $this->assertEquals('rgb(255 255 255 / 100%)', $instance->value);

        $instance->red = 0;
        $instance->green = 0;
        $instance->blue = 0;
        $instance->alpha = 0;

        $this->assertEquals('rgb(0 0 0 / 0%)', $instance->value);

        $instance->red = 128;
        $instance->green = 128;
        $instance->blue = 128;
        $instance->alpha = 50;

        $this->assertEquals('rgb(128 128 128 / 50%)', $instance->value);

        $instance->red = 25;
        $instance->green = 50;
        $instance->blue = 100;
        $instance->alpha = 10;

        $this->assertEquals('rgb(25 50 100 / 10%)', $instance->value);

        $instance->red = 12.3;
        $instance->green = 45.6;
        $instance->blue = 7.89;
        $instance->alpha = 1.23;

        $this->assertEquals('rgb(12.3 45.6 7.89 / 1.23%)', $instance->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $instance = new ColorRgb('rgb(0 0 0 / 0)');

        try {
            $instance->red = 256;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->green = 256;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->blue = 256;
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
            $instance->red = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->green = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->blue = -1;
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
        new ColorRgb($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $instance = new ColorRgb(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new ColorRgb(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $instance = new ColorRgb('rgb(0 0 0 / 0%)', new TypeOptionsDTO(immutable: false));

        $newInstance = 'rgb(255 255 255 / 100%)';

        $instance->value = $newInstance;
        $this->assertEquals($newInstance, $instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $instance = new ColorRgb('rgb(0 0 0 / 0%)', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $instance->value = 'rgb(255 255 255 / 100%)';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $instance = ColorRgb::nullable();
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        ColorRgb::immutable('rgb(0 0 0 / 0%)')->value = 'rgb(255 255 255 / 100%)';
    }
}
