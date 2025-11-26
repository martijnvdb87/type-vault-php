<?php

namespace Martijnvdb\TypeVault\Utils;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Type;

class Collection
{
    protected readonly string $type;

    /** @var array<Type> */
    protected array $value;

    /**
     * @param array<Type> $value
     */
    public function __construct(string $type, array $value)
    {
        $this->type = $type;
        $this->value = [];

        foreach ($value as $item) {
            $this->push($item);
        }
    }

    public function push(Type ...$value): void
    {
        foreach ($value as $item) {
            if (!($item instanceof $this->type)) {
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
