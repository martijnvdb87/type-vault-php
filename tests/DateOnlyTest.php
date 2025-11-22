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
            $instance = new DateOnly($value['input']);
            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItShouldReturnTheCorrectPropertyValues(): void
    {
        foreach ($this->values as $value) {
            $instance = new DateOnly($value['input']);
            $this->assertEquals($value['year'], $instance->year);
            $this->assertEquals($value['month'], $instance->month);
            $this->assertEquals($value['day'], $instance->day);
        }
    }

    public function testItCanUpdateThePropertyValues(): void
    {
        foreach ($this->values as $value) {
            $instance = DateOnly::nullable();

            $instance->year = $value['year'];
            $instance->month = $value['month'];
            $instance->day = $value['day'];

            $this->assertEquals($value['year'], $instance->year);
            $this->assertEquals($value['month'], $instance->month);
            $this->assertEquals($value['day'], $instance->day);

            $this->assertEquals($value['output'], $instance->value);
        }
    }

    public function testItCanModifyPropertyValues(): void
    {
        $instance = new DateOnly('0000-01-01');

        $instance->year = 9999;
        $instance->month = 12;
        $instance->day = 31;

        $this->assertEquals('9999-12-31', $instance->value);

        $instance->year = 0;
        $instance->month = 1;
        $instance->day = 1;

        $this->assertEquals('0000-01-01', $instance->value);

        $instance->year = 2000;
        $instance->month = 6;
        $instance->day = 15;

        $this->assertEquals('2000-06-15', $instance->value);

        $instance->year = 2024;
        $instance->month = 2;
        $instance->day = 29;

        $this->assertEquals('2024-02-29', $instance->value);
    }

    public function testItShouldThrowAnErrorIfTheValueIsOutOfAllowedRange(): void
    {
        $instance = new DateOnly('0000-01-01');

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

        $this->assertEquals('0000-01-01', $instance->value);
    }

    public function testItThrowsAnErrorIfTheValueIsChangedWhenImmutable(): void
    {
        $instance = DateOnly::immutable('0000-01-01');

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

        $this->assertEquals('0000-01-01', $instance->value);
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
        $instanceString = '2000-01-01';
        $instance = new DateOnly($instanceString);

        $this->assertInstanceOf(\DateTime::class, $instance->toDateTime());
        $this->assertEquals($instanceString, $instance->toDateTime()->format('Y-m-d'));
    }

    public function testItShouldReturnDateOnlyFromDateTime(): void
    {
        $instance = DateOnly::fromDateTime(new \DateTime('2000-01-01'));
        $this->assertEquals('2000-01-01', $instance->value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $instance = new DateOnly(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new DateOnly(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $instance = new DateOnly('2000-01-01', new TypeOptionsDTO(immutable: false));

        $newInstance = '2001-02-02';

        $instance->value = $newInstance;
        $this->assertEquals($newInstance, $instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $instance = new DateOnly('2000-01-01', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $instance->value = '2001-02-02';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $instance = DateOnly::nullable();
        $this->assertNull($instance->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        DateOnly::immutable('2000-01-01')->value = '2001-02-02';
    }
}
