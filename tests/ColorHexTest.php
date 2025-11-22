<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\ColorHex;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class ColorHexTest extends TestCase
{
    /** @var array<int, array<string, int|string>> */
    private array $values = [
        ['input' => '#000000', 'output' => '#000000ff', 'red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 255],
        ['input' => '#FFFFFF', 'output' => '#ffffffff', 'red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 255],
        ['input' => '#FF0000', 'output' => '#ff0000ff', 'red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 255],
        ['input' => '#00FF00', 'output' => '#00ff00ff', 'red' => 0, 'green' => 255, 'blue' => 0, 'alpha' => 255],
        ['input' => '#0000FF', 'output' => '#0000ffff', 'red' => 0, 'green' => 0, 'blue' => 255, 'alpha' => 255],
        ['input' => '#FFFF00', 'output' => '#ffff00ff', 'red' => 255, 'green' => 255, 'blue' => 0, 'alpha' => 255],
        ['input' => '#00FFFF', 'output' => '#00ffffff', 'red' => 0, 'green' => 255, 'blue' => 255, 'alpha' => 255],
        ['input' => '#FF00FF', 'output' => '#ff00ffff', 'red' => 255, 'green' => 0, 'blue' => 255, 'alpha' => 255],
        ['input' => '#888888', 'output' => '#888888ff', 'red' => 136, 'green' => 136, 'blue' => 136, 'alpha' => 255],
        ['input' => '#CCCCCC', 'output' => '#ccccccff', 'red' => 204, 'green' => 204, 'blue' => 204, 'alpha' => 255],
        ['input' => '#000', 'output' => '#000000ff', 'red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 255],
        ['input' => '#FFF', 'output' => '#ffffffff', 'red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 255],
        ['input' => '#F00', 'output' => '#ff0000ff', 'red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 255],
        ['input' => '#0F0', 'output' => '#00ff00ff', 'red' => 0, 'green' => 255, 'blue' => 0, 'alpha' => 255],
        ['input' => '#00F', 'output' => '#0000ffff', 'red' => 0, 'green' => 0, 'blue' => 255, 'alpha' => 255],
        ['input' => '#FF0', 'output' => '#ffff00ff', 'red' => 255, 'green' => 255, 'blue' => 0, 'alpha' => 255],
        ['input' => '#0FF', 'output' => '#00ffffff', 'red' => 0, 'green' => 255, 'blue' => 255, 'alpha' => 255],
        ['input' => '#F0F', 'output' => '#ff00ffff', 'red' => 255, 'green' => 0, 'blue' => 255, 'alpha' => 255],
        ['input' => '#888', 'output' => '#888888ff', 'red' => 136, 'green' => 136, 'blue' => 136, 'alpha' => 255],
        ['input' => '#CCC', 'output' => '#ccccccff', 'red' => 204, 'green' => 204, 'blue' => 204, 'alpha' => 255],
        ['input' => '#00000000', 'output' => '#00000000', 'red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 0],
        ['input' => '#FFFFFFFF', 'output' => '#ffffffff', 'red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 255],
        [
            'input' => '#FF0000F0',
            'output' => '#ff0000f0',
            'red' => 255,
            'green' => 0,
            'blue' => 0,
            'alpha' => 240,
        ],
        [
            'input' => '#00FF000F',
            'output' => '#00ff000f',
            'red' => 0,
            'green' => 255,
            'blue' => 0,
            'alpha' => 15,
        ],
        ['input' => '#0000FF00', 'output' => '#0000ff00', 'red' => 0, 'green' => 0, 'blue' => 255, 'alpha' => 0],
        [
            'input' => '#FFFF00F0',
            'output' => '#ffff00f0',
            'red' => 255,
            'green' => 255,
            'blue' => 0,
            'alpha' => 240,
        ],
        [
            'input' => '#00FFFF0F',
            'output' => '#00ffff0f',
            'red' => 0,
            'green' => 255,
            'blue' => 255,
            'alpha' => 15,
        ],
        ['input' => '#FF00FFFF', 'output' => '#ff00ffff', 'red' => 255, 'green' => 0, 'blue' => 255, 'alpha' => 255],
        [
            'input' => '#88888888',
            'output' => '#88888888',
            'red' => 136,
            'green' => 136,
            'blue' => 136,
            'alpha' => 136,
        ],
        [
            'input' => '#CCCCCCCC',
            'output' => '#cccccccc',
            'red' => 204,
            'green' => 204,
            'blue' => 204,
            'alpha' => 204,
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $instance = new ColorHex($value['input']);
            $this->assertEquals($value['output'], $instance->value);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function invalidDataSet(): array
    {
        return [
            ['foo'],
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
        new ColorHex($value);
    }

    public function testItShouldReturnTheCorrectColorValues(): void
    {
        foreach ($this->values as $value) {
            $instance = new ColorHex($value['input']);
            $this->assertEquals($value['red'], $instance->red);
            $this->assertEquals($value['green'], $instance->green);
            $this->assertEquals($value['blue'], $instance->blue);
            $this->assertEquals($value['alpha'], $instance->alpha);
        }
    }

    public function testItCanUpdateTheColorValues(): void
    {
        foreach ($this->values as $value) {
            $instance = ColorHex::nullable();

            $instance->red = $value['red'];
            $instance->green = $value['green'];
            $instance->blue = $value['blue'];
            $instance->alpha = $value['alpha'];

            $this->assertEquals($value['red'], $instance->red);
            $this->assertEquals($value['green'], $instance->green);
            $this->assertEquals($value['blue'], $instance->blue);
            $this->assertEquals($value['alpha'], $instance->alpha);

            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItCanModifyPropertyValues(): void
    {
        $instance = new ColorHex('#00000000');

        $instance->red = 255;
        $instance->green = 255;
        $instance->blue = 255;
        $instance->alpha = 255;

        $this->assertEquals('#ffffffff', $instance->value);

        $instance->red = 0;
        $instance->green = 0;
        $instance->blue = 0;
        $instance->alpha = 0;

        $this->assertEquals('#00000000', $instance->value);

        $instance->red = 128;
        $instance->green = 128;
        $instance->blue = 128;
        $instance->alpha = 128;

        $this->assertEquals('#80808080', $instance->value);

        $instance->red = 25;
        $instance->green = 50;
        $instance->blue = 100;
        $instance->alpha = 150;

        $this->assertEquals('#19326496', $instance->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $instance = new ColorHex('#ffffffff');

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
            $instance->alpha = 256;
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

        $this->assertEquals('#ffffffff', $instance->value);
    }

    public function testItThrowsAnErrorIfTheValueIsChangedWhenImmutable(): void
    {
        $instance = ColorHex::immutable('#00000000');

        try {
            $instance->red = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->green = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->blue = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->alpha = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        $this->assertEquals('#00000000', $instance->value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $instance = new ColorHex(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new ColorHex(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $instance = new ColorHex('#ffffffff', new TypeOptionsDTO(immutable: false));

        $newInstance = '#00000000';

        $instance->value = $newInstance;
        $this->assertEquals($newInstance, $instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $instance = new ColorHex('#ffffffff', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $instance->value = '#00000000';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $instance = ColorHex::nullable();
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        ColorHex::immutable('#ffffffff')->value = '#00000000';
    }
}
