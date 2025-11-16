<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\TimeOnly;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class TimeOnlyTest extends TestCase
{
    /** @var array<int, array<string, string>> */
    private array $values = [
        [
            'input' => '0:0:0',
            'output' => '00:00:00.000',
        ],
        [
            'input' => '1:2:3.4',
            'output' => '01:02:03.400',
        ],
        [
            'input' => '01:23:45.123',
            'output' => '01:23:45.123',
        ],
        [
            'input' => '01:23:45',
            'output' => '01:23:45.000',
        ],
        [
            'input' => '00:00:00.000',
            'output' => '00:00:00.000',
        ],
        [
            'input' => '23:59:59.999',
            'output' => '23:59:59.999',
        ],
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $timeOnly = new TimeOnly($value['input']);
            $this->assertEquals($value['output'], $timeOnly->value);
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
