<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\DateOnly;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class DateOnlyTest extends TestCase
{
    /** @var array<int, array<string, int|string>> */
    private array $values = [
        [
            'input' => '0-1-1',
            'output' => '0000-01-01',
            'year' => 0,
            'month' => 1,
            'day' => 1,
        ],
        [
            'input' => '1-1-1',
            'output' => '0001-01-01',
            'year' => 1,
            'month' => 1,
            'day' => 1,
        ],
        [
            'input' => '1000-01-01',
            'output' => '1000-01-01',
            'year' => 1000,
            'month' => 1,
            'day' => 1,
        ],
        [
            'input' => '9999-12-31',
            'output' => '9999-12-31',
            'year' => 9999,
            'month' => 12,
            'day' => 31,
        ],
        [
            'input' => '2000-01-01',
            'output' => '2000-01-01',
            'year' => 2000,
            'month' => 1,
            'day' => 1,
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $dateOnly = new DateOnly($value['input']);
            $this->assertEquals($value['output'], $dateOnly->value);
        }
    }

    public function testItShouldReturnTheCorrectPropertyValues(): void
    {
        foreach ($this->values as $value) {
            $color = new DateOnly($value['input']);
            $this->assertEquals($value['year'], $color->year);
            $this->assertEquals($value['month'], $color->month);
            $this->assertEquals($value['day'], $color->day);
        }
    }

    public function testItCanUpdateThePropertyValues(): void
    {
        foreach ($this->values as $value) {
            $dateOnly = DateOnly::nullable();

            $dateOnly->year = $value['year'];
            $dateOnly->month = $value['month'];
            $dateOnly->day = $value['day'];

            $this->assertEquals($value['year'], $dateOnly->year);
            $this->assertEquals($value['month'], $dateOnly->month);
            $this->assertEquals($value['day'], $dateOnly->day);

            $this->assertEquals($value['output'], $dateOnly->value);
        }
    }

    public function testItCanModifyColorValues(): void
    {
        $dateOnly = new DateOnly('0000-01-01');

        $dateOnly->year = 9999;
        $dateOnly->month = 12;
        $dateOnly->day = 31;

        $this->assertEquals('9999-12-31', $dateOnly->value);

        $dateOnly->year = 0;
        $dateOnly->month = 1;
        $dateOnly->day = 1;

        $this->assertEquals('0000-01-01', $dateOnly->value);

        $dateOnly->year = 2000;
        $dateOnly->month = 6;
        $dateOnly->day = 15;

        $this->assertEquals('2000-06-15', $dateOnly->value);

        $dateOnly->year = 2024;
        $dateOnly->month = 2;
        $dateOnly->day = 29;

        $this->assertEquals('2024-02-29', $dateOnly->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $dateOnly = new DateOnly('0000-01-01');

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

        $this->assertEquals('0000-01-01', $dateOnly->value);
    }

    public function testItThrowsAnErrorIfTheValueIsChangedWhenImmutable(): void
    {
        $dateOnly = DateOnly::immutable('0000-01-01');

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

        $this->assertEquals('0000-01-01', $dateOnly->value);
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
        new DateOnly($value);
    }

    public function testItShouldReturnDateTime(): void
    {
        $dateOnlyString = '2000-01-01';
        $dateOnly = new DateOnly($dateOnlyString);

        $this->assertInstanceOf(\DateTime::class, $dateOnly->toDateTime());
        $this->assertEquals($dateOnlyString, $dateOnly->toDateTime()->format('Y-m-d'));
    }

    public function testItShouldReturnDateOnlyFromDateTime(): void
    {
        $dateOnly = DateOnly::fromDateTime(new \DateTime('2000-01-01'));
        $this->assertEquals('2000-01-01', $dateOnly->value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $dateOnly = new DateOnly(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($dateOnly->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new DateOnly(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $dateOnly = new DateOnly('2000-01-01', new TypeOptionsDTO(immutable: false));

        $newDateOnly = '2001-02-02';

        $dateOnly->value = $newDateOnly;
        $this->assertEquals($newDateOnly, $dateOnly->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $dateOnly = new DateOnly('2000-01-01', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $dateOnly->value = '2001-02-02';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $dateOnly = DateOnly::nullable();
        $this->assertNull($dateOnly->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        DateOnly::immutable('2000-01-01')->value = '2001-02-02';
    }
}
