<?php

namespace Martijnvdb\TypeVault\Types;

class Month extends BaseString
{
    private static $monthInstances = [];

    private static function getMonthInstance(string $monthName): Month
    {
        if (!isset(Month::$monthInstances[$monthName])) {
            Month::$monthInstances[$monthName] = Month::immutable($monthName);
        }

        return Month::$monthInstances[$monthName];
    }

    public static function January(): Month
    {
        return Month::getMonthInstance('january');
    }

    public static function February(): Month
    {
        return Month::getMonthInstance('february');
    }

    public static function March(): Month
    {
        return Month::getMonthInstance('march');
    }

    public static function April(): Month
    {
        return Month::getMonthInstance('april');
    }

    public static function May(): Month
    {
        return Month::getMonthInstance('may');
    }

    public static function June(): Month
    {
        return Month::getMonthInstance('june');
    }

    public static function July(): Month
    {
        return Month::getMonthInstance('july');
    }

    public static function August(): Month
    {
        return Month::getMonthInstance('august');
    }

    public static function September(): Month
    {
        return Month::getMonthInstance('september');
    }

    public static function October(): Month
    {
        return Month::getMonthInstance('october');
    }

    public static function November(): Month
    {
        return Month::getMonthInstance('november');
    }

    public static function December(): Month
    {
        return Month::getMonthInstance('december');
    }

    protected function validate(mixed $value): bool
    {
        if (!parent::validate($value)) {
            return false;
        }

        return in_array($value, [
            'january',
            'february',
            'march',
            'april',
            'may',
            'june',
            'july',
            'august',
            'september',
            'october',
            'november',
            'december'
        ]);
    }
}
