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

        $this->assertEquals([
            new Integer(1),
            new Integer(2),
            new Integer(3),
            new Integer(4),
            new Integer(5),
            new Integer(6),
        ], $collection->toArray());
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

    public function testEveryMethodToReturnTrue(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $this->assertTrue($collection->every(fn($item) => $item instanceof Integer));
    }

    public function testEveryMethodToReturnFalse(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $this->assertFalse($collection->every(fn($item) => $item instanceof FloatingPoint));
    }

    public function testFilterMethod(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $filtered = $collection->filter(fn($item) => $item->value < 3);

        $this->assertEquals([
            new Integer(1),
            new Integer(2),
        ], $filtered->toArray());
    }

    public function testFindMethodReturnsItem(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $found = $collection->find(fn($item) => $item->value === 2);

        $this->assertEquals(new Integer(2), $found);
    }

    public function testFindMethodReturnsNull(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $found = $collection->find(fn($item) => $item->value === 4);

        $this->assertNull($found);
    }

    public function testFindIndexMethodReturnsIndex(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $found = $collection->findIndex(fn($item) => $item->value === 2);

        $this->assertEquals(1, $found);
    }

    public function testFindIndexMethodReturnsNull(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $found = $collection->findIndex(fn($item) => $item->value === 4);

        $this->assertNull($found);
    }

    public function testForEachMethod(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $result = [];

        $collection->forEach(function ($item) use (&$result) {
            $result[] = $item->value;
        });

        $this->assertEquals([1, 2, 3], $result);
    }

    public function testIncludesMethod(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $this->assertTrue($collection->includes(new Integer(2)));
        $this->assertFalse($collection->includes(new Integer(4)));
    }

    public function testIndexOfMethod(): void
    {
        $firstItem = new Integer(1);
        $secondItem = new Integer(2);
        $thirdItem = new Integer(3);

        $collection = new Collection(Integer::class, [
            $firstItem,
            $secondItem,
            $thirdItem,
        ]);

        $this->assertEquals(0, $collection->indexOf($firstItem));
        $this->assertEquals(1, $collection->indexOf($secondItem));
        $this->assertEquals(2, $collection->indexOf($thirdItem));
        $this->assertNull($collection->indexOf(new Integer(4)));
    }

    public function testLastIndexOfMethod(): void
    {
        $firstItem = new Integer(1);
        $secondItem = new Integer(2);
        $thirdItem = new Integer(3);

        $collection = new Collection(Integer::class, [
            $firstItem,
            $secondItem,
            $thirdItem,
            $secondItem,
            $firstItem,
        ]);

        $this->assertEquals(4, $collection->lastIndexOf($firstItem));
        $this->assertEquals(3, $collection->lastIndexOf($secondItem));
        $this->assertEquals(2, $collection->lastIndexOf($thirdItem));
        $this->assertNull($collection->lastIndexOf(new Integer(4)));
    }

    public function testMapMethod(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $mapped = $collection->map(fn($item) => $item->value * 2);

        $this->assertEquals([2, 4, 6], $mapped);
    }

    public function testPopMethod(): void
    {
        $toBePopped = new Integer(3);

        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            $toBePopped,
        ]);

        $item = $collection->pop();

        $this->assertEquals($toBePopped, $item);
        $this->assertEquals(2, $collection->length);
    }

    public function testPushMethod(): void
    {
        $collection = new Collection(Integer::class);
        $collection->push(new Integer(1), new Integer(2), new Integer(3));

        $this->assertEquals(3, $collection->length);
    }

    public function testReduceMethod(): void
    {
        $collection = new Collection(Integer::class, [
            new Integer(1),
            new Integer(2),
            new Integer(3),
        ]);

        $result = $collection->reduce(fn($carry, $item) => $carry + $item->value, 0);

        $this->assertEquals(6, $result);
    }

    public function testLengthMethod(): void
    {
        $collection = new Collection(Integer::class);
        $this->assertEquals(0, $collection->length);

        $collection->push(new Integer(1), new Integer(2), new Integer(3));
        $this->assertEquals(3, $collection->length);
    }

    public function testTypeMethod(): void
    {
        $collection = new Collection(Integer::class);
        $this->assertEquals(Integer::class, $collection->type);
    }
}
