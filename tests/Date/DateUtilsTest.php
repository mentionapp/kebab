<?php

namespace Mention\Kebab\Tests\Date;

use Mention\Kebab\Clock\Clock;
use Mention\Kebab\Date\DateUtils;
use PHPUnit\Framework\TestCase;

class DateUtilsTest extends TestCase
{
    public function testFromString(): void
    {
        Clock::enableMocking(0.0); // Let's travel through time!
        self::assertEquals('1970-01-01', DateUtils::now()->format('Y-m-d'));

        $day = DateUtils::fromString('+1 day');
        self::assertEquals('1970-01-02', $day->format('Y-m-d'));

        $day = DateUtils::fromString('1970-01-03');
        self::assertEquals('1970-01-03', $day->format('Y-m-d'));

        $day = DateUtils::fromString('04-01-1970', 'd-m-Y');
        self::assertEquals('1970-01-04', $day->format('Y-m-d'));

        $day = DateUtils::fromString('86400', 'U');
        self::assertEquals('86400', $day->getTimestamp());
        self::assertEquals('Europe/Paris', $day->getTimezone()->getName());
        self::assertEquals('1970-01-02 01:00:00', $day->format('Y-m-d H:i:s'));

        $day = DateUtils::fromString('@86400');
        self::assertEquals('86400', $day->getTimestamp());
        self::assertEquals('+00:00', $day->getTimezone()->getName());
        self::assertEquals('1970-01-02 00:00:00', $day->format('Y-m-d H:i:s'));

        $day = DateUtils::fromString('04-01-1970 00:00:00+12:00');
        self::assertEquals('1970-01-04', $day->format('Y-m-d'));
        self::assertEquals('+12:00', $day->getTimezone()->getName());
    }

    public function testFromStringMutable(): void
    {
        Clock::enableMocking(0.0); // Let's travel through time!
        self::assertEquals('1970-01-01', DateUtils::now()->format('Y-m-d'));

        $day = DateUtils::fromStringMutable('+1 day');
        self::assertEquals('1970-01-02', $day->format('Y-m-d'));

        $day = DateUtils::fromStringMutable('1970-01-03');
        self::assertEquals('1970-01-03', $day->format('Y-m-d'));

        $day = DateUtils::fromStringMutable('04-01-1970', 'd-m-Y');
        self::assertEquals('1970-01-04', $day->format('Y-m-d'));
    }

    public function testFromStringTz(): void
    {
        Clock::enableMocking(0.0); // Let's travel through time!
        self::assertEquals('1970-01-01', DateUtils::now()->format('Y-m-d'));

        // Relative dates

        $tz = DateUtils::fromStringTz('+1 day', new \DateTimeZone('America/New_York'));
        self::assertEquals('1970-01-01 19:00:00', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('America/New_York', $tz->getTimezone()->getName());
        self::assertEquals('86400', $tz->format('U'));

        $tz = DateUtils::fromStringTz('+1 day', new \DateTimeZone('Europe/Berlin'));
        self::assertEquals('1970-01-02 01:00:00', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('Europe/Berlin', $tz->getTimezone()->getName());
        self::assertEquals('86400', $tz->format('U'));

        $tz = DateUtils::fromStringTz('+1 day', new \DateTimeZone('Asia/Tokyo'));
        self::assertEquals('1970-01-02 09:00:00', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('Asia/Tokyo', $tz->getTimezone()->getName());
        self::assertEquals('86400', $tz->format('U'));

        // Absolute date

        $tz = DateUtils::fromStringTz('1990-03-19 01:02:03', new \DateTimeZone('Asia/Tokyo'), 'Y-m-d H:i:s');
        self::assertEquals('1990-03-19 01:02:03', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('Asia/Tokyo', $tz->getTimezone()->getName());
        self::assertEquals('637776123', $tz->format('U'));

        $tz = DateUtils::fromStringTz('1990-03-19 01:02:03', new \DateTimeZone('Asia/Tokyo'));
        self::assertEquals('1990-03-19 01:02:03', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('Asia/Tokyo', $tz->getTimezone()->getName());
        self::assertEquals('637776123', $tz->format('U'));

        // Tricky relative dates

        $tz = DateUtils::fromStringTz('first day of January 2019 00:00:00', new \DateTimeZone('America/New_York'));
        self::assertEquals('2019-01-01 00:00:00', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('America/New_York', $tz->getTimezone()->getName());
        self::assertEquals('1546318800', $tz->format('U'));

        $tz = DateUtils::fromStringTz('first day of January 2019 00:00:00', new \DateTimeZone('Europe/Berlin'));
        self::assertEquals('2019-01-01 00:00:00', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('Europe/Berlin', $tz->getTimezone()->getName());
        self::assertEquals('1546297200', $tz->format('U'));

        $tz = DateUtils::fromStringTz('first day of January 2019 00:00:00', new \DateTimeZone('Asia/Tokyo'));
        self::assertEquals('2019-01-01 00:00:00', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('Asia/Tokyo', $tz->getTimezone()->getName());
        self::assertEquals('1546268400', $tz->format('U'));

        // Timestamp

        $tz = DateUtils::fromStringTz('1546268400', new \DateTimeZone('Asia/Tokyo'), 'U');
        self::assertEquals('Asia/Tokyo', $tz->getTimezone()->getName());
        self::assertEquals('2019-01-01 00:00:00', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('1546268400', $tz->getTimestamp());

        // @timestamp behaves like setTimestamp() and overrides the timezone

        $tz = DateUtils::fromStringTz('@1546268400', new \DateTimeZone('Asia/Tokyo'));
        self::assertEquals('+00:00', $tz->getTimezone()->getName());
        self::assertEquals('2018-12-31 15:00:00', $tz->format('Y-m-d H:i:s'));
        self::assertEquals('1546268400', $tz->getTimestamp());
    }

    public function testToMutable(): void
    {
        $dateImmutable = new \DateTimeImmutable();
        self::assertEquals(
            $dateImmutable,
            \DateTimeImmutable::createFromMutable(DateUtils::toMutable($dateImmutable))
        );
        self::assertEquals(
            $dateImmutable->getTimezone(),
            DateUtils::toMutable($dateImmutable)->getTimezone()
        );

        $dateImmutable = new \DateTimeImmutable('19-03-1990');
        self::assertEquals(
            $dateImmutable,
            \DateTimeImmutable::createFromMutable(DateUtils::toMutable($dateImmutable))
        );
        self::assertEquals(
            $dateImmutable->getTimezone(),
            DateUtils::toMutable($dateImmutable)->getTimezone()
        );

        $dateImmutable = new \DateTimeImmutable('19-03-1990', new \DateTimeZone('Asia/Tokyo'));
        self::assertEquals(
            $dateImmutable,
            \DateTimeImmutable::createFromMutable(DateUtils::toMutable($dateImmutable))
        );
        self::assertEquals(
            $dateImmutable->getTimezone(),
            DateUtils::toMutable($dateImmutable)->getTimezone()
        );

        $dateImmutable = new \DateTimeImmutable('+1 day', new \DateTimeZone('Asia/Tokyo'));
        self::assertEquals(
            $dateImmutable,
            \DateTimeImmutable::createFromMutable(DateUtils::toMutable($dateImmutable))
        );
        self::assertEquals(
            $dateImmutable->getTimezone(),
            DateUtils::toMutable($dateImmutable)->getTimezone()
        );
    }

    public function testNowTz(): void
    {
        Clock::enableMocking(0.0); // Let's travel through time!
        self::assertEquals(
            '1969-12-31 19:00:00',
            DateUtils::nowTz(new \DateTimeZone('America/New_York'))->format('Y-m-d H:i:s')
        );
        self::assertEquals(
            '1970-01-01 01:00:00',
            DateUtils::nowTz(new \DateTimeZone('Europe/Berlin'))->format('Y-m-d H:i:s')
        );
        self::assertEquals(
            '1970-01-01 09:00:00',
            DateUtils::nowTz(new \DateTimeZone('Asia/Tokyo'))->format('Y-m-d H:i:s')
        );
    }
}
