<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\FloatingPoint;
use Martijnvdb\TypeVault\Types\Integer;
use Martijnvdb\TypeVault\Utils\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testItSetsValueCorrectly(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        foreach ($collection->toArray() as $entry) {
            $this->assertInstanceOf(Integer::class, $entry);
        }
    }

    public function testItThrowsExceptionWhenTypeDoesNotMatch(): void
    {
        $this->expectException(TypeVaultValidationError::class);

        new Collection(FloatingPoint::class, [
            new FloatingPoint(1),
            new FloatingPoint(2),
            new Integer(3),
        ]);
    }

    public function testConcatMethod(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $other = new Collection(Integer::class, [
            new Integer(4),
            new Integer(5),
            new Integer(6),
        ]);

        $collection->concat($other);

        $this->assertEquals($collection->toArray(), [
            new Integer(1),
            new Integer(2),
            new Integer(3),
            new Integer(4),
            new Integer(5),
            new Integer(6),
        ]);
    }

    public function testConcatMethodThrowsExceptionWhenTypesDontMatch(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $other = new Collection(FloatingPoint::class, [
            new FloatingPoint(4),
            new FloatingPoint(5),
            new FloatingPoint(6),
        ]);

        $this->expectException(TypeVaultValidationError::class);
        $collection->concat($other);
    }

    public function testLengthMethod(): void
    {
        $collection = new Collection(Integer::class);
        $this->assertEquals($collection->length, 0);

        $collection->push(new Integer(1), new Integer(2), new Integer(3));
        $this->assertEquals($collection->length, 3);
    }

    public function testTypeMethod(): void
    {
        $collection = new Collection(Integer::class);
        $this->assertEquals($collection->type, Integer::class);
    }
}
