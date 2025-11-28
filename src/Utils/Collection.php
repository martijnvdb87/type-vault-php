<?php

namespace Martijnvdb\TypeVault\Utils;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Type;

class Collection
{
    protected readonly string $collectionType;

    /** @var array<Type> */
    protected array $value;

    /**
     * @param array<Type> $value
     */
    public function __construct(string $type, array $value = [])
    {
        $this->collectionType = $type;
        $this->value = [];

        foreach ($value as $item) {
            $this->push($item);
        }
    }

    public string $type {
        get => $this->collectionType;
    }

    public int $length {
        get => count($this->value);
    }

    public function concat(Collection $collection): void
    {
        if ($this->type !== $collection->type) {
            throw new TypeVaultValidationError();
        }

        $this->push(...$collection->toArray());
    }

    public function every(callable $callback): bool
    {
        foreach ($this->value as $item) {
            if (!$callback($item)) {
                return false;
            }
        }

        return true;
    }

    public function push(Type ...$value): void
    {
        foreach ($value as $item) {
            if (!($item instanceof $this->collectionType)) {
                throw new TypeVaultValidationError();
            }

            $this->value[] = $item;
        }
    }

    /**
     * @return array<Type>
     */
    public function toArray(): array
    {
        return $this->value;
    }
}
