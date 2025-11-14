<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\DateOnlyValuesDTO;

class DateOnly extends BaseString
{
    public static function fromDateTime(\DateTime $dateTime): self
    {
        return new self($dateTime->format('Y-m-d'));
    }

    public function toDateTime(): \DateTime | null
    {
        return isset($this->value) ? new \DateTime($this->value) : null;
    }

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return $this->isValidFormat(strval($value));
    }

    protected function modifier(mixed $value): string
    {
        $value = parent::modifier($value);

        $matches = $this->getComponents(strval($value));

        if ($matches) {
            return (new DateOnlyValuesDTO(
                year: $matches["year"],
                month: $matches["month"],
                day: $matches["day"]
            ))->__toString();
        }

        return $value;
    }

    /**
     * @return int[]|null
     */
    private function getComponents(string $value): array | null
    {
        $matches = $this->matchPattern($value);

        if ($matches === null) {
            return null;
        }

        $year = $matches[1];
        $month = $matches[2];
        $day = $matches[3];

        return [
            "year" => $year ? intval($year) : 0,
            "month" => $month ? intval($month) : 0,
            "day" => $day ? intval($day) : 0
        ];
    }

    private function isValidFormat(string $value): bool
    {
        return $this->matchPattern($value) !== null;
    }

    /**
     * @return string[]|null
     */
    private function matchPattern(string $value): array | null
    {
        preg_match('/^(\d{1,4})-(\d{1,2})-(\d{1,2})$/', strval($value), $matches);

        if (!$matches) {
            return null;
        }

        return $matches;
    }
}
