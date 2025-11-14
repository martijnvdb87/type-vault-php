<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\DateOnly;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class DateOnlyTest extends TestCase
{
    /** @var array<int, array<string, string>> */
    private array $values = [
        [
            'input' => '0-1-1',
            'output' => '0000-01-01',
        ],
        [
            'input' => '1-1-1',
            'output' => '0001-01-01',
        ],
        [
            'input' => '1000-01-01',
            'output' => '1000-01-01',
        ],
        [
            'input' => '9999-12-31',
            'output' => '9999-12-31',
        ],
        [
            'input' => '2000-01-01',
            'output' => '2000-01-01',
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $dateOnly = new DateOnly($value['input']);
            $this->assertEquals($value['output'], $dateOnly->value);
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
