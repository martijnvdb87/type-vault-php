<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\Types\Email;
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
}
