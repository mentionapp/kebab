<?php

namespace Mention\PhpUtils\Clock;

/**
 * Clock is a collection of mockable time-related functions.
 *
 * Once mocking is enabled, all functions will stop using the real system
 * clock, and will start to use a fixed time instead. sleep() and usleep() will
 * return immediately, after having incremented the time accordingly.
 *
 * Enable mocking by calling enableMocking(). Mocking can be disabled again
 * with disableMocking().
 */
class Clock
{
    /** @var ?float */
    private static $now;

    public static function enableMocking(?float $time = null): void
    {
        self::$now = $time ?? microtime(true);
    }

    public static function disableMocking(): void
    {
        self::$now = null;
    }

    public static function microtimeFloat(): float
    {
        if (null === self::$now) {
            return microtime(true);
        }

        return self::$now;
    }

    public static function microtimeString(): string
    {
        if (null === self::$now) {
            return microtime(false);
        }

        return sprintf('%0.6f %d', self::$now - (int) self::$now, (int) self::$now);
    }

    public static function time(): int
    {
        if (null === self::$now) {
            return time();
        }

        return (int) self::$now;
    }

    /**
     * @return int|false
     */
    public static function sleep(int $s)
    {
        if (null === self::$now) {
            return sleep($s);
        }

        self::$now += $s;

        return 0;
    }

    public static function usleep(int $us): void
    {
        if (null === self::$now) {
            usleep($us);

            return;
        }

        self::$now += (float) $us / 1000000;
    }
}
