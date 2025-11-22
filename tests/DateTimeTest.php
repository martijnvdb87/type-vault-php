<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\DateTime;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /** @var array<int, array<string, int|string>> */
    private array $values = [
        [
            'input' => '1-2-3T4:5:6.7Z',
            'output' => '0001-02-03T04:05:06.700Z',
            'year' => 1,
            'month' => 2,
            'day' => 3,
            'hour' => 4,
            'minute' => 5,
            'second' => 6,
            'microsecond' => 700,
        ],
        [
            'input' => '0001-01-02T01:23:45.123Z',
            'output' => '0001-01-02T01:23:45.123Z',
            'year' => 1,
            'month' => 1,
            'day' => 2,
            'hour' => 1,
            'minute' => 23,
            'second' => 45,
            'microsecond' => 123,
        ],
        [
            'input' => '2023-01-02T01:23:45.123Z',
            'output' => '2023-01-02T01:23:45.123Z',
            'year' => 2023,
            'month' => 1,
            'day' => 2,
            'hour' => 1,
            'minute' => 23,
            'second' => 45,
            'microsecond' => 123,
        ],
        [
            'input' => '2023-01-02T01:23:45Z',
            'output' => '2023-01-02T01:23:45.000Z',
            'year' => 2023,
            'month' => 1,
            'day' => 2,
            'hour' => 1,
            'minute' => 23,
            'second' => 45,
            'microsecond' => 0,
        ],
        [
            'input' => '1000-01-01T00:00:00.000Z',
            'output' => '1000-01-01T00:00:00.000Z',
            'year' => 1000,
            'month' => 1,
            'day' => 1,
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
            'microsecond' => 0,
        ],
        [
            'input' => '9999-12-31T23:59:59.999Z',
            'output' => '9999-12-31T23:59:59.999Z',
            'year' => 9999,
            'month' => 12,
            'day' => 31,
            'hour' => 23,
            'minute' => 59,
            'second' => 59,
            'microsecond' => 999,
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $instance = new DateTime($value['input']);
            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItShouldReturnTheCorrectPropertyValues(): void
    {
        foreach ($this->values as $value) {
            $instance = new DateTime($value['input']);
            $this->assertEquals($value['year'], $instance->year);
            $this->assertEquals($value['month'], $instance->month);
            $this->assertEquals($value['day'], $instance->day);
            $this->assertEquals($value['hour'], $instance->hour);
            $this->assertEquals($value['minute'], $instance->minute);
            $this->assertEquals($value['second'], $instance->second);
            $this->assertEquals($value['microsecond'], $instance->microsecond);
        }
    }

    public function testItCanUpdateThePropertyValues(): void
    {
        foreach ($this->values as $value) {
            $instance = DateTime::nullable();

            $instance->year = $value['year'];
            $instance->month = $value['month'];
            $instance->day = $value['day'];
            $instance->hour = $value['hour'];
            $instance->minute = $value['minute'];
            $instance->second = $value['second'];
            $instance->microsecond = $value['microsecond'];

            $this->assertEquals($value['year'], $instance->year);
            $this->assertEquals($value['month'], $instance->month);
            $this->assertEquals($value['day'], $instance->day);
            $this->assertEquals($value['hour'], $instance->hour);
            $this->assertEquals($value['minute'], $instance->minute);
            $this->assertEquals($value['second'], $instance->second);
            $this->assertEquals($value['microsecond'], $instance->microsecond);

            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItCanModifyPropertyValues(): void
    {
        $instance = new DateTime('0000-01-01T00:00:00.000Z');

        $instance->year = 9999;
        $instance->month = 12;
        $instance->day = 31;
        $instance->hour = 23;
        $instance->minute = 59;
        $instance->second = 59;
        $instance->microsecond = 999;

        $this->assertEquals('9999-12-31T23:59:59.999Z', $instance->value);

        $instance->year = 0;
        $instance->month = 1;
        $instance->day = 1;
        $instance->hour = 0;
        $instance->minute = 0;
        $instance->second = 0;
        $instance->microsecond = 0;

        $this->assertEquals('0000-01-01T00:00:00.000Z', $instance->value);

        $instance->year = 2000;
        $instance->month = 6;
        $instance->day = 15;
        $instance->hour = 12;
        $instance->minute = 34;
        $instance->second = 56;
        $instance->microsecond = 78;

        $this->assertEquals('2000-06-15T12:34:56.078Z', $instance->value);

        $instance->year = 2024;
        $instance->month = 2;
        $instance->day = 29;
        $instance->hour = 23;
        $instance->minute = 59;
        $instance->second = 59;
        $instance->microsecond = 999;

        $this->assertEquals('2024-02-29T23:59:59.999Z', $instance->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $instance = new DateTime('0000-01-01T00:00:00.000Z');

        try {
            $instance->year = 10_000;
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->month = 13;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->day = 32;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

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
            $instance->year = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->month = 0;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $instance->day = 0;
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

        $this->assertEquals('0000-01-01T00:00:00.000Z', $instance->value);
    }

    public function testItThrowsAnErrorIfTheValueIsChangedWhenImmutable(): void
    {
        $instance = DateTime::immutable('0000-01-01T00:00:00.000Z');

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

        try {
            $instance->microsecond = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        $this->assertEquals('0000-01-01T00:00:00.000Z', $instance->value);
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
        new DateTime($value);
    }

    public function testItShouldReturnDateTime(): void
    {
        $instanceString = '2000-01-01T01:23:45.123Z';
        $instance = new DateTime($instanceString);

        $this->assertInstanceOf(\DateTime::class, $instance->toDateTime());
        $this->assertEquals($instanceString, $this->getDateTimeFormat($instance->toDateTime()));
    }

    public function testItShouldReturnDateTimeFromDateTime(): void
    {
        $instance = DateTime::fromDateTime(new \DateTime('2000-01-01T01:23:45.123Z'));
        $this->assertEquals('2000-01-01T01:23:45.123Z', $instance->value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $instance = new DateTime(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new DateTime(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $instance = new DateTime('2000-01-01T01:23:45.123Z', new TypeOptionsDTO(immutable: false));

        $newInstance = '2001-02-02T12:45:06.456Z';

        $instance->value = $newInstance;
        $this->assertEquals($newInstance, $instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $instance = new DateTime('2000-01-01T01:23:45.123Z', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $instance->value = '2001-02-02T12:45:06.456Z';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $instance = DateTime::nullable();
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        DateTime::immutable('2000-01-01T01:23:45.123Z')->value = '2001-02-02T12:45:06.456Z';
    }

    private function getDateTimeFormat(\DateTime $instance): string
    {
        $microsecondString = substr($instance->format('u'), 0, 3);

        return $instance->format('Y-m-d\TH:i:s') . '.' . $microsecondString . 'Z';
    }
}
