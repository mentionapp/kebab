<?php

namespace Mention\Kebab\Clock;

use Mention\Kebab\Clock\Exception\IncompatibleSystemException;

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
    /**
     * @var ?float number of seconds since the Unix Epoch (January 1
     *             1970 00:00:00 GMT) or null if not mocked
     */
    private static ?float $now;

    /**
     * @param null|float $time number of seconds
     */
    public static function enableMocking(?float $time = null): void
    {
        self::travelTo($time ?? microtime(true));
    }

    public static function disableMocking(): void
    {
        self::$now = null;
    }

    /**
     * @see https://www.php.net/manual/en/function.microtime.php
     *
     * @return float number of seconds
     */
    public static function microtimeFloat(): float
    {
        if (null === self::$now) {
            return microtime(true);
        }

        return self::$now;
    }

    /**
     * @see https://www.php.net/manual/en/function.microtime.php
     *
     * @return string of the form "0.32107100 1668868618"
     */
    public static function microtimeString(): string
    {
        if (null === self::$now) {
            return microtime(false);
        }

        return sprintf('%0.6f %d', self::$now - (int) self::$now, (int) self::$now);
    }

    /**
     * @see https://www.php.net/manual/en/function.time.php
     *
     * @return int number of seconds
     */
    public static function time(): int
    {
        if (null === self::$now) {
            return time();
        }

        return (int) self::$now;
    }

    /**
     * @see https://www.php.net/manual/en/function.sleep.php
     *
     * @param positive-int|0 $s number of seconds
     *
     * @return int an error code or a number of remaining seconds
     */
    public static function sleep(int $s): int
    {
        if (null === self::$now) {
            $i = sleep($s);

            if (false === $i) {
                // Since PHP 8.0 sleep does not return false anymore and throws instead.
                // Emulating behavior here for consistency.
                throw new \ValueError();
            }

            return $i;
        }

        self::travelTo(self::$now + $s);

        return 0;
    }

    /**
     * @see https://www.php.net/manual/en/function.usleep.php
     *
     * @param positive-int|0 $us number of microseconds (seconds/1e+6)
     */
    public static function usleep(int $us): void
    {
        if (null === self::$now) {
            usleep($us);

            return;
        }

        self::travelTo(self::$now + (float) $us / 1000000);
    }

    /**
     * @see https://www.php.net/manual/en/function.hrtime.php
     *
     * @return array{0: int, 1: int}
     */
    public static function hrtimeArray(): array
    {
        if (null === self::$now) {
            return hrtime(false);
        }

        $seconds = (int) self::$now;

        return [
            // Whole seconds
            $seconds,
            // Remaining nanoseconds
            (int) round((self::$now - $seconds) * 1e+9),
        ];
    }

    /**
     * @see https://www.php.net/manual/en/function.hrtime.php
     *
     * Must be used under php 64bit version.
     *
     * @throws IncompatibleSystemException if used with php 32bit version
     *
     * @return int number of nanoseconds (seconds/1e+9)
     */
    public static function hrtimeInt(): int
    {
        if (is_float(hrtime(true))) {
            // Can't cast float to int safely
            throw IncompatibleSystemException::expected64();
        }

        if (null === self::$now) {
            return hrtime(true);
        }

        // On php 64bit version it will be ok to cast until year 2262
        return (int) (self::$now * 1e+9);
    }

    /**
     * @see https://www.php.net/manual/en/function.hrtime.php
     *
     * Can be safely used under php 32bit and 64 bit versions.
     *
     * @return float number of nanoseconds (seconds/1e+9)
     */
    public static function hrtimeFloat(): float
    {
        if (null === self::$now) {
            return (float) hrtime(true);
        }

        return self::$now * 1e+9;
    }

    /**
     * Since we need to be able to cast $now from float to int we
     * are forbidding to set a date too far in the future.
     */
    private static function travelTo(float $now): void
    {
        if ($now > PHP_INT_MAX) {
            throw new \LogicException(sprintf(
                'Trying to travel in the future after year %d is not supported',
                PHP_INT_MAX / 60 / 60 / 24 / 7 / 365,
            ));
        }

        self::$now = $now;
    }
}
