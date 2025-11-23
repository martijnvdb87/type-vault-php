<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\TimeOnly;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class TimeOnlyTest extends TestCase
{
    /** @var array<int, array<string, int|string>> */
    private array $values = [
        [
            'input' => '0:0:0',
            'output' => '00:00:00.000',
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
            'microsecond' => 0,
        ],
        [
            'input' => '1:2:3.4',
            'output' => '01:02:03.400',
            'hour' => 1,
            'minute' => 2,
            'second' => 3,
            'microsecond' => 400,
        ],
        [
            'input' => '01:23:45.123',
            'output' => '01:23:45.123',
            'hour' => 1,
            'minute' => 23,
            'second' => 45,
            'microsecond' => 123,
        ],
        [
            'input' => '01:23:45',
            'output' => '01:23:45.000',
            'hour' => 1,
            'minute' => 23,
            'second' => 45,
            'microsecond' => 0,
        ],
        [
            'input' => '00:00:00.000',
            'output' => '00:00:00.000',
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
            'microsecond' => 0,
        ],
        [
            'input' => '23:59:59.999',
            'output' => '23:59:59.999',
            'hour' => 23,
            'minute' => 59,
            'second' => 59,
            'microsecond' => 999,
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $timeOnly = new TimeOnly($value['input']);
            $this->assertEquals($value['output'], $timeOnly->value);
        }
    }



    public function testItShouldReturnTheCorrectPropertyValues(): void
    {
        foreach ($this->values as $value) {
            $instance = new TimeOnly($value['input']);
            $this->assertEquals($value['hour'], $instance->hour);
            $this->assertEquals($value['minute'], $instance->minute);
            $this->assertEquals($value['second'], $instance->second);
            $this->assertEquals($value['microsecond'], $instance->microsecond);
        }
    }

    public function testItCanUpdateThePropertyValues(): void
    {
        foreach ($this->values as $value) {
            $instance = TimeOnly::nullable();

            $instance->hour = $value['hour'];
            $instance->minute = $value['minute'];
            $instance->second = $value['second'];
            $instance->microsecond = $value['microsecond'];

            $this->assertEquals($value['hour'], $instance->hour);
            $this->assertEquals($value['minute'], $instance->minute);
            $this->assertEquals($value['second'], $instance->second);
            $this->assertEquals($value['microsecond'], $instance->microsecond);

            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItCanModifyPropertyValues(): void
    {
        $instance = new TimeOnly('00:00:00.000');

        $instance->hour = 23;
        $instance->minute = 59;
        $instance->second = 59;
        $instance->microsecond = 999;

        $this->assertEquals('23:59:59.999', $instance->value);

        $instance->hour = 0;
        $instance->minute = 0;
        $instance->second = 0;
        $instance->microsecond = 0;

        $this->assertEquals('00:00:00.000', $instance->value);

        $instance->hour = 12;
        $instance->minute = 34;
        $instance->second = 56;
        $instance->microsecond = 78;

        $this->assertEquals('12:34:56.078', $instance->value);

        $instance->hour = 23;
        $instance->minute = 59;
        $instance->second = 59;
        $instance->microsecond = 999;

        $this->assertEquals('23:59:59.999', $instance->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $instance = new TimeOnly('00:00:00.000');

        try {
            $instance->hour = 24;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->minute = 60;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->second = 60;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->microsecond = 1000;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->hour = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->minute = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->second = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->microsecond = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        $this->assertEquals('00:00:00.000', $instance->value);
    }

    public function testItThrowsAnErrorIfTheValueIsChangedWhenImmutable(): void
    {
        $instance = TimeOnly::immutable('00:00:00.000');

        try {
            $instance->hour = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->minute = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->second = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->microsecond = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        $this->assertEquals('00:00:00.000', $instance->value);
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
            ['24:23:45'],
            ['01:23:45.1000'],
            ['23:60:59.999'],
            ['01:00:60.000'],
            ['24:60:60.999'],
        ];
    }

    #[DataProviderExternal(self::class, 'invalidDataSet')]
    public function testItShouldThrowExceptionWhenValueIsInvalid(mixed $value): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new TimeOnly($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $timeOnly = new TimeOnly(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($timeOnly->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new TimeOnly(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $timeOnly = new TimeOnly('0:0:0', new TypeOptionsDTO(immutable: false));

        $newTimeOnly = '01:23:45.123';

        $timeOnly->value = $newTimeOnly;
        $this->assertEquals($newTimeOnly, $timeOnly->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $timeOnly = new TimeOnly('0:0:0', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $timeOnly->value = '01:23:45.123';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $timeOnly = TimeOnly::nullable();
        $this->assertNull($timeOnly->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        TimeOnly::immutable('0:0:0')->value = '01:23:45.123';
    }
}
