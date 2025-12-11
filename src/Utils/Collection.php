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

    public function clone(Collection $collection): Collection
    {
        return new Collection($collection->type, $collection->map(fn($item) => $item->clone()));
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

    public function filter(callable $callback): Collection
    {
        return new Collection($this->collectionType, array_filter($this->value, $callback));
    }

    public function find(callable $callback): ?Type
    {
        return array_find($this->value, $callback);
    }

    public function findIndex(callable $callback): ?int
    {
        for ($i = 0; $i < count($this->value); $i++) {
            if ($callback($this->value[$i])) {
                return $i;
            }
        }

        return null;
    }

    public function forEach(callable $callback): void
    {
        foreach ($this->value as $item) {
            $callback($item);
        }
    }

    public function includes(Type $value): bool
    {
        return in_array($value, $this->value);
    }

    public function indexOf(Type $value): ?int
    {
        return array_search($value, $this->value) ?: null;
    }

    public function lastIndexOf(Type $value): ?int
    {
        for ($i = count($this->value) - 1; $i >= 0; $i--) {
            if ($this->value[$i] === $value) {
                return $i;
            }
        }

        return null;
    }

    /**
     * @return array<mixed>
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->value);
    }

    public function pop(): ?Type
    {
        return array_pop($this->value);
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

    public function reduce(callable $callback, mixed $initialValue): mixed
    {
        return array_reduce($this->value, $callback, $initialValue);
    }

    public function reverse(): Collection
    {
        $reversed = array_reverse($this->value);
        $this->value = $reversed;

        return $this;
    }

    public function shift(): ?Type
    {
        return array_shift($this->value);
    }

    public function some(callable $callback): bool
    {
        return array_find($this->value, $callback) !== null;
    }

    public function sort(callable|null $callback = null): Collection
    {
        $callback ? usort($this->value, $callback) : sort($this->value);

        return $this;
    }

    public function splice(int $start, int $deleteCount): Collection
    {
        $spliced = new Collection($this->collectionType, array_splice($this->value, $start, $deleteCount));

        return $spliced;
    }

    /**
     * @return array<Type>
     */
    public function toArray(): array
    {
        return $this->value;
    }

    public function toString(): string
    {
        return join(', ', $this->map(fn($item) => $item->toString()));
    }

    public function unshift(Type ...$value): void
    {
        $this->value = array_merge($value, $this->value);
    }

    /**
     * @return array<Type>
     */
    public function values(): array
    {
        return $this->toArray();
    }
}
