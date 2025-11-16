<?php

namespace Martijnvdb\TypeVault\Types;

class Weekday extends BaseString
{
    /** @var array<string, Weekday> */
    private static $weekdayInstances = [];

    private static function getWeekdayInstance(string $weekdayName): Weekday
    {
        if (!isset(Weekday::$weekdayInstances[$weekdayName])) {
            Weekday::$weekdayInstances[$weekdayName] = Weekday::immutable($weekdayName);
        }

        return Weekday::$weekdayInstances[$weekdayName];
    }

    public static function Monday(): Weekday
    {
        return Weekday::getWeekdayInstance('monday');
    }

    public static function Tuesday(): Weekday
    {
        return Weekday::getWeekdayInstance('tuesday');
    }

    public static function Wednesday(): Weekday
    {
        return Weekday::getWeekdayInstance('wednesday');
    }

    public static function Thursday(): Weekday
    {
        return Weekday::getWeekdayInstance('thursday');
    }

    public static function Friday(): Weekday
    {
        return Weekday::getWeekdayInstance('friday');
    }

    public static function Saturday(): Weekday
    {
        return Weekday::getWeekdayInstance('saturday');
    }

    public static function Sunday(): Weekday
    {
        return Weekday::getWeekdayInstance('sunday');
    }

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return in_array($value, [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday'
        ]);
    }
}
