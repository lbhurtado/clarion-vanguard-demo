<?php

namespace App;

use libphonenumber\PhoneNumberFormat;
//use League\Csv\Reader;

class Mobile
{
    public $number;

    /**
     * Mobile constructor.
     * @param $number
     */
    public function __construct($number)
    {
        $this->number = phone_format($number, 'PH', PhoneNumberFormat::E164);
    }

    public static function number($number)
    {
        return (new static($number))->number;
    }

    public static function national($number)
    {
        $number = (new static($number))->number;

        return str_replace(' ', '', phone_format($number, 'PH', PhoneNumberFormat::NATIONAL));
    }

//    public static function blacklist()
//    {
//        $reader = Reader::createFromPath(database_path('blacklist.csv'));
//        $numbers = [];
//        foreach ($reader as $index => $row) {
//            $numbers [] = static::number($row[0]);
//        }
//        return $numbers;
//    }
}
