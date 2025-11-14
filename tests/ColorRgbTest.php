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
            $color = new ColorRgb($value['input']);
            $this->assertEquals($value['output'], $color->value);
        }
    }

    public function testItShouldReturnTheCorrectColorValues(): void
    {
        foreach ($this->values as $value) {
            $color = new ColorRgb($value['input']);
            $this->assertEquals($value['red'], $color->red);
            $this->assertEquals($value['green'], $color->green);
            $this->assertEquals($value['blue'], $color->blue);
            $this->assertEquals($value['alpha'], $color->alpha);
        }
    }

    public function testItCanUpdateTheColorValues(): void
    {
        foreach ($this->values as $value) {
            $color = ColorRgb::nullable();

            $color->red = $value['red'];
            $color->green = $value['green'];
            $color->blue = $value['blue'];
            $color->alpha = $value['alpha'];

            $this->assertEquals($value['output'], $color->value);
        }
    }

    public function testItCanModifyColorValues(): void
    {
        $color = new ColorRgb('rgb(0, 0, 0)');

        $color->red = 255;
        $color->green = 255;
        $color->blue = 255;
        $color->alpha = 100;

        $this->assertEquals('rgb(255 255 255 / 100%)', $color->value);

        $color->red = 0;
        $color->green = 0;
        $color->blue = 0;
        $color->alpha = 0;

        $this->assertEquals('rgb(0 0 0 / 0%)', $color->value);

        $color->red = 128;
        $color->green = 128;
        $color->blue = 128;
        $color->alpha = 50;

        $this->assertEquals('rgb(128 128 128 / 50%)', $color->value);

        $color->red = 25;
        $color->green = 50;
        $color->blue = 100;
        $color->alpha = 10;

        $this->assertEquals('rgb(25 50 100 / 10%)', $color->value);

        $color->red = 12.3;
        $color->green = 45.6;
        $color->blue = 7.89;
        $color->alpha = 1.23;

        $this->assertEquals('rgb(12.3 45.6 7.89 / 1.23%)', $color->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $color = new ColorRgb('rgb(0 0 0 / 0)');

        try {
            $color->red = 256;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->green = 256;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->blue = 256;
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
            $color->red = -1;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->green = -1;
            throw new \Exception();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $color->blue = -1;
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
        new ColorRgb($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $color = new ColorRgb(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($color->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new ColorRgb(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $color = new ColorRgb('rgb(0 0 0 / 0%)', new TypeOptionsDTO(immutable: false));

        $newColorRgb = 'rgb(255 255 255 / 100%)';

        $color->value = $newColorRgb;
        $this->assertEquals($newColorRgb, $color->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $color = new ColorRgb('rgb(0 0 0 / 0%)', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $color->value = 'rgb(255 255 255 / 100%)';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $color = ColorRgb::nullable();
        $this->assertNull($color->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        ColorRgb::immutable('rgb(0 0 0 / 0%)')->value = 'rgb(255 255 255 / 100%)';
    }
}
