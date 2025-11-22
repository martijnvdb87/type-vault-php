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
            $dateTime = new DateTime($value['input']);
            $this->assertEquals($value['output'], $dateTime->value);
        }
    }



    public function testItShouldReturnTheCorrectPropertyValues(): void
    {
        foreach ($this->values as $value) {
            $color = new DateTime($value['input']);
            $this->assertEquals($value['year'], $color->year);
            $this->assertEquals($value['month'], $color->month);
            $this->assertEquals($value['day'], $color->day);
            $this->assertEquals($value['hour'], $color->hour);
            $this->assertEquals($value['minute'], $color->minute);
            $this->assertEquals($value['second'], $color->second);
            $this->assertEquals($value['microsecond'], $color->microsecond);
        }
    }

    public function testItCanUpdateThePropertyValues(): void
    {
        foreach ($this->values as $value) {
            $dateOnly = DateTime::nullable();

            $dateOnly->year = $value['year'];
            $dateOnly->month = $value['month'];
            $dateOnly->day = $value['day'];
            $dateOnly->hour = $value['hour'];
            $dateOnly->minute = $value['minute'];
            $dateOnly->second = $value['second'];
            $dateOnly->microsecond = $value['microsecond'];

            $this->assertEquals($value['year'], $dateOnly->year);
            $this->assertEquals($value['month'], $dateOnly->month);
            $this->assertEquals($value['day'], $dateOnly->day);
            $this->assertEquals($value['hour'], $dateOnly->hour);
            $this->assertEquals($value['minute'], $dateOnly->minute);
            $this->assertEquals($value['second'], $dateOnly->second);
            $this->assertEquals($value['microsecond'], $dateOnly->microsecond);

            $this->assertEquals($value['output'], $dateOnly->value);
        }
    }

    public function testItCanModifyColorValues(): void
    {
        $dateOnly = new DateTime('0000-01-01T00:00:00.000Z');

        $dateOnly->year = 9999;
        $dateOnly->month = 12;
        $dateOnly->day = 31;
        $dateOnly->hour = 23;
        $dateOnly->minute = 59;
        $dateOnly->second = 59;
        $dateOnly->microsecond = 999;

        $this->assertEquals('9999-12-31T23:59:59.999Z', $dateOnly->value);

        $dateOnly->year = 0;
        $dateOnly->month = 1;
        $dateOnly->day = 1;
        $dateOnly->hour = 0;
        $dateOnly->minute = 0;
        $dateOnly->second = 0;
        $dateOnly->microsecond = 0;

        $this->assertEquals('0000-01-01T00:00:00.000Z', $dateOnly->value);

        $dateOnly->year = 2000;
        $dateOnly->month = 6;
        $dateOnly->day = 15;
        $dateOnly->hour = 12;
        $dateOnly->minute = 34;
        $dateOnly->second = 56;
        $dateOnly->microsecond = 78;

        $this->assertEquals('2000-06-15T12:34:56.078Z', $dateOnly->value);

        $dateOnly->year = 2024;
        $dateOnly->month = 2;
        $dateOnly->day = 29;
        $dateOnly->hour = 23;
        $dateOnly->minute = 59;
        $dateOnly->second = 59;
        $dateOnly->microsecond = 999;

        $this->assertEquals('2024-02-29T23:59:59.999Z', $dateOnly->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $dateOnly = new DateTime('0000-01-01T00:00:00.000Z');

        try {
            $dateOnly->year = 10_000;
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->month = 13;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->day = 32;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->hour = 24;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->minute = 60;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->second = 60;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->microsecond = 1000;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->year = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->month = 0;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->day = 0;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->hour = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->minute = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->second = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->microsecond = -1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        $this->assertEquals('0000-01-01T00:00:00.000Z', $dateOnly->value);
    }

    public function testItThrowsAnErrorIfTheValueIsChangedWhenImmutable(): void
    {
        $dateOnly = DateTime::immutable('0000-01-01T00:00:00.000Z');

        try {
            $dateOnly->year = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->month = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->day = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->hour = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->minute = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->second = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        try {
            $dateOnly->microsecond = 1;
            $this->fail();
        } catch (TypeVaultValidationError $error) {
            $this->assertInstanceOf(TypeVaultValidationError::class, $error);
        }

        $this->assertEquals('0000-01-01T00:00:00.000Z', $dateOnly->value);
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
        $DateTimeString = '2000-01-01T01:23:45.123Z';
        $dateTime = new DateTime($DateTimeString);

        $this->assertInstanceOf(\DateTime::class, $dateTime->toDateTime());
        $this->assertEquals($DateTimeString, $this->getDateTimeFormat($dateTime->toDateTime()));
    }

    public function testItShouldReturnDateTimeFromDateTime(): void
    {
        $dateTime = DateTime::fromDateTime(new \DateTime('2000-01-01T01:23:45.123Z'));
        $this->assertEquals('2000-01-01T01:23:45.123Z', $dateTime->value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $dateTime = new DateTime(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($dateTime->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new DateTime(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $dateTime = new DateTime('2000-01-01T01:23:45.123Z', new TypeOptionsDTO(immutable: false));

        $newDateTime = '2001-02-02T12:45:06.456Z';

        $dateTime->value = $newDateTime;
        $this->assertEquals($newDateTime, $dateTime->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $dateTime = new DateTime('2000-01-01T01:23:45.123Z', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $dateTime->value = '2001-02-02T12:45:06.456Z';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $dateTime = DateTime::nullable();
        $this->assertNull($dateTime->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        DateTime::immutable('2000-01-01T01:23:45.123Z')->value = '2001-02-02T12:45:06.456Z';
    }

    private function getDateTimeFormat(\DateTime $dateTime): string
    {
        $microsecondString = substr($dateTime->format('u'), 0, 3);

        return $dateTime->format('Y-m-d\TH:i:s') . '.' . $microsecondString . 'Z';
    }
}
