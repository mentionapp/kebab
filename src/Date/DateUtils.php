<?php

namespace Mention\PhpUtils\Date;

use Assert\Assertion;
use Mention\PhpUtils\Clock\Clock;

/**
 * DateUtils implements common datetime operations.
 *
 * Uses Clock internally, so the time is mockable in tests.
 */
class DateUtils
{
    /**
     * Max allowed digits in the 'u' format.
     *
     * Ref: http://php.net/manual/en/datetime.createfromformat.php
     */
    private const MAX_MICRO_DIGITS = 6;

    /**
     * Returns a \DateTimeImmutable initialized to the current time.
     */
    public static function now(): \DateTimeImmutable
    {
        $datetime = \DateTimeImmutable::createFromFormat(
            'U u',
            self::nowString()
        );

        if (false === $datetime) {
            throw new \RuntimeException('Failed creating datetime');
        }

        return $datetime;
    }

    /**
     * Returns a \DateTime initialized to the current time.
     */
    public static function nowMutable(): \DateTime
    {
        $datetime = \DateTime::createFromFormat(
            'U u',
            self::nowString()
        );

        if (false === $datetime) {
            throw new \RuntimeException('Failed creating datetime');
        }

        return $datetime;
    }

    /**
     * Parses a string to a \DateTimeImmutable.
     */
    public static function fromString(string $str, ?string $format = null): \DateTimeImmutable
    {
        if (null !== $format) {
            $datetime = \DateTimeImmutable::createFromFormat($format, $str);

            if (false === $datetime) {
                throw new \InvalidArgumentException(sprintf(
                    'Can not parse date with format `%s`: `%s`',
                    $format,
                    $str
                ));
            }

            return $datetime;
        }

        return new \DateTimeImmutable($str);
    }

    /**
     * Parses a string to a \DateTime.
     */
    public static function fromStringMutable(string $str, ?string $format = null): \DateTime
    {
        $dateTimeImmutable = self::fromString($str, $format);

        return self::toMutable($dateTimeImmutable);
    }

    /**
     * Returns a \DateTimeImmutable initialized to the given timestamp.
     *
     * @param int $timestamp Timestamp in seconds
     * @param int $micros    Microseconds
     */
    public static function fromTimestamp(int $timestamp, int $micros = 0): \DateTimeImmutable
    {
        Assertion::range($micros, 0, 999999, 'Parameter $micros is out of range [0, 999999]: %d');

        $datetime = \DateTimeImmutable::createFromFormat(
            'U u',
            $timestamp.' '.$micros
        );

        if (false === $datetime) {
            throw new \InvalidArgumentException(sprintf(
                'Can not parse timestamp/micros: `%s`/`%s`',
                $timestamp,
                $micros
            ));
        }

        return $datetime;
    }

    /**
     * Returns a \DateTimeImmutable initialized to the given timestamp (in milliseconds).
     *
     * @param int $timestampMs Timestamp in milliseconds
     */
    public static function fromTimestampMs(int $timestampMs): \DateTimeImmutable
    {
        $seconds = intval($timestampMs / 1000);
        $micros = ($timestampMs % 1000) * 1000;

        return self::fromTimestamp($seconds, $micros);
    }

    /**
     * Converts a \DateTimeInterface to a \DateTime.
     *
     * Returns the given datetime if it's already a \DateTime
     */
    public static function toMutable(\DateTimeInterface $datetime): \DateTime
    {
        if ($datetime instanceof \DateTime) {
            return $datetime;
        }

        $mutable = \DateTime::createFromFormat('U u', $datetime->format('U u'));

        if (false === $mutable) {
            throw new \RuntimeException('Convertion to mutable failed');
        }

        return $mutable;
    }

    private static function nowString(): string
    {
        [$microPart, $ts] = explode(' ', Clock::microtimeString());

        return sprintf(
            '%s %s',
            $ts,
            substr($microPart, 2, self::MAX_MICRO_DIGITS)
        );
    }
}
