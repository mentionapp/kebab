<?php

namespace Mention\Kebab\Tests\Date;

use Mention\Kebab\Clock\Clock;
use Mention\Kebab\Date\DateUtils;
use PHPUnit\Framework\TestCase;

class DateUtilsTest extends TestCase
{
    public function testFromString()
    {
        Clock::enableMocking(0.0); // Let's travel through time!
        self::assertEquals('1970-01-01', DateUtils::now()->format('Y-m-d'));

        $day2 = DateUtils::fromString('+1 day');
        self::assertEquals('1970-01-02', $day2->format('Y-m-d'));

        $day3 = DateUtils::fromString('1970-01-03');
        self::assertEquals('1970-01-03', $day3->format('Y-m-d'));

        $day4 = DateUtils::fromString('04-01-1970', 'd-m-Y');
        self::assertEquals('1970-01-04', $day4->format('Y-m-d'));
    }

    public function testFromStringMutable()
    {
        Clock::enableMocking(0.0); // Let's travel through time!
        self::assertEquals('1970-01-01', DateUtils::now()->format('Y-m-d'));

        $day2 = DateUtils::fromStringMutable('+1 day');
        self::assertEquals('1970-01-02', $day2->format('Y-m-d'));

        $day3 = DateUtils::fromStringMutable('1970-01-03');
        self::assertEquals('1970-01-03', $day3->format('Y-m-d'));

        $day4 = DateUtils::fromStringMutable('04-01-1970', 'd-m-Y');
        self::assertEquals('1970-01-04', $day4->format('Y-m-d'));
    }
}
