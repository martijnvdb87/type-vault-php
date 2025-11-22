<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Duration;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class DurationTest extends TestCase
{
    /** @var array<int, array<string, float|string>> */
    private array $values = [
        [
            'input' => 'P3Y6M4DT12H30M5S',
            'output' => 'P3Y6M0W4DT12H30M5S',
            'year' => 3,
            'month' => 6,
            'week' => 0,
            'day' => 4,
            'hour' => 12,
            'minute' => 30,
            'second' => 5,
        ],
        [
            'input' => 'P0Y0M0DT0H0M0S',
            'output' => 'P0Y0M0W0DT0H0M0S',
            'year' => 0,
            'month' => 0,
            'week' => 0,
            'day' => 0,
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
        ],
        [
            'input' => 'P1Y',
            'output' => 'P1Y0M0W0DT0H0M0S',
            'year' => 1,
            'month' => 0,
            'week' => 0,
            'day' => 0,
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
        ],
        [
            'input' => 'P1M',
            'output' => 'P0Y1M0W0DT0H0M0S',
            'year' => 0,
            'month' => 1,
            'week' => 0,
            'day' => 0,
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
        ],
        [
            'input' => 'P1D',
            'output' => 'P0Y0M0W1DT0H0M0S',
            'year' => 0,
            'month' => 0,
            'week' => 0,
            'day' => 1,
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
        ],
        [
            'input' => 'PT1H',
            'output' => 'P0Y0M0W0DT1H0M0S',
            'year' => 0,
            'month' => 0,
            'week' => 0,
            'day' => 0,
            'hour' => 1,
            'minute' => 0,
            'second' => 0,
        ],
        [
            'input' => 'PT1M',
            'output' => 'P0Y0M0W0DT0H1M0S',
            'year' => 0,
            'month' => 0,
            'week' => 0,
            'day' => 0,
            'hour' => 0,
            'minute' => 1,
            'second' => 0,
        ],
        [
            'input' => 'PT1S',
            'output' => 'P0Y0M0W0DT0H0M1S',
            'year' => 0,
            'month' => 0,
            'week' => 0,
            'day' => 0,
            'hour' => 0,
            'minute' => 0,
            'second' => 1,
        ],
        [
            'input' => 'P0.1MT2.3S',
            'output' => 'P0Y0.1M0W0DT0H0M2.3S',
            'year' => 0,
            'month' => 0.1,
            'week' => 0,
            'day' => 0,
            'hour' => 0,
            'minute' => 0,
            'second' => 2.3,
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $instance = new Duration($value['input']);
            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItShouldReturnTheCorrectPropertyValues(): void
    {
        foreach ($this->values as $value) {
            $instance = new Duration($value['input']);
            $this->assertEquals($value['year'], $instance->year);
            $this->assertEquals($value['month'], $instance->month);
            $this->assertEquals($value['week'], $instance->week);
            $this->assertEquals($value['day'], $instance->day);
            $this->assertEquals($value['hour'], $instance->hour);
            $this->assertEquals($value['minute'], $instance->minute);
            $this->assertEquals($value['second'], $instance->second);
        }
    }

    public function testItCanUpdateThePropertyValues(): void
    {
        foreach ($this->values as $value) {
            $instance = Duration::nullable();

            $instance->year = $value['year'];
            $instance->month = $value['month'];
            $instance->week = $value['week'];
            $instance->day = $value['day'];
            $instance->hour = $value['hour'];
            $instance->minute = $value['minute'];
            $instance->second = $value['second'];

            $this->assertEquals($value['year'], $instance->year);
            $this->assertEquals($value['month'], $instance->month);
            $this->assertEquals($value['week'], $instance->week);
            $this->assertEquals($value['day'], $instance->day);
            $this->assertEquals($value['hour'], $instance->hour);
            $this->assertEquals($value['minute'], $instance->minute);
            $this->assertEquals($value['second'], $instance->second);

            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItCanModifyPropertyValues(): void
    {
        $instance = new Duration('P0Y0M0W0DT0H0M0S');

        $instance->year = 9999;
        $instance->month = 12;
        $instance->week = 52;
        $instance->day = 31;
        $instance->hour = 23;
        $instance->minute = 59;
        $instance->second = 59;

        $this->assertEquals('P9999Y12M52W31DT23H59M59S', $instance->value);

        $instance->year = 0;
        $instance->month = 1;
        $instance->week = 0;
        $instance->day = 1;
        $instance->hour = 0;
        $instance->minute = 0;
        $instance->second = 0;

        $this->assertEquals('P0Y1M0W1DT0H0M0S', $instance->value);

        $instance->year = 2000;
        $instance->month = 6;
        $instance->week = 26;
        $instance->day = 15;
        $instance->hour = 12;
        $instance->minute = 34;
        $instance->second = 56;

        $this->assertEquals('P2000Y6M26W15DT12H34M56S', $instance->value);

        $instance->year = 2024;
        $instance->month = 2;
        $instance->week = 1;
        $instance->day = 29;
        $instance->hour = 23;
        $instance->minute = 59;
        $instance->second = 59;

        $this->assertEquals('P2024Y2M1W29DT23H59M59S', $instance->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $instance = new Duration('P0Y0M0W0DT0H0M0S');

        try {
            $instance->year = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->month = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->day = -1;
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

        $this->assertEquals('P0Y0M0W0DT0H0M0S', $instance->value);
    }

    public function testItThrowsAnErrorIfTheValueIsChangedWhenImmutable(): void
    {
        $instance = Duration::immutable('P0Y0M0W0DT0H0M0S');

        try {
            $instance->year = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->month = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->day = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

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

        $this->assertEquals('P0Y0M0W0DT0H0M0S', $instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsSupported(): void
    {
        $values = [
            '3Y6M4DT12H30M5S',
            'P3Y6M4D12H30M5S',
            '',
            'P3Y6M4DT12H30M5',
            '1234567890',
            'P3Y6M4DT12H30M5Sx',
            '2000-01-01T00:00:00.000Z',
            '2000-01-01',
            '00:00:00',
        ];

        $this->expectException(TypeVaultValidationError::class);

        foreach ($values as $value) {
            new Duration($value);
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
        new Duration($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $instance = new Duration(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Duration(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $instance = new Duration('P3Y6M4DT12H30M5S', new TypeOptionsDTO(immutable: false));

        $newInstance = 'P0Y0M0W0DT0H0M0S';

        $instance->value = $newInstance;
        $this->assertEquals($newInstance, $instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $instance = new Duration('P3Y6M4DT12H30M5S', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $instance->value = 'P0Y0M0W0DT0H0M0S';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $instance = Duration::nullable();
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Duration::immutable('P3Y6M4DT12H30M5S')->value = 'P0Y0M0W0DT0H0M0S';
    }
}
