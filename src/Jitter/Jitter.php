<?php

namespace Mention\Kebab\Jitter;

class Jitter
{
    private const UINT32_MAX = 0xFFFFFFFF;

    /**
     * Randomizes $value by adding or removing up to $value*factor
     *
     * Adding Jitter helps to avoid thundering herds problems, by reducing
     * the chances that many events will occur at exactly the same time.
     *
     * @return int Returns a number in the range [$value*(1-$factor), $value*(1+$factor)]
     */
    public static function random(int $value, float $factor): int
    {
        if ($factor > 1 || $factor < 0) {
            throw new \OutOfRangeException(sprintf(
                '$factor must be a value in the range [0,1], `%s` given',
                $factor,
            ));
        }

        $jitter = 1 - $factor + (rand() / getrandmax() * $factor * 2);

        return (int) floor($value * $jitter);
    }

    /**
     * Same as random(), but use a stable jitter
     *
     * Jitter is computed as a function of $key. This avoids averaging jitter
     * over time.
     */
    public static function stable(int $value, string $key, float $factor): int
    {
        if ($factor > 1 || $factor < 0) {
            throw new \OutOfRangeException(sprintf(
                '$factor must be a value in the range [0,1], `%s` given',
                $factor,
            ));
        }

        $jitter = 1 - $factor + (crc32($key) / self::UINT32_MAX * $factor * 2);

        return (int) floor($value * $jitter);
    }

    /**
     * Returns a value in the range [$min,$max]
     *
     * The value is computed as a function of $key. This avoids averaging jitter
     * over time.
     */
    public static function rangeStable(int $min, int $max, string $key): int
    {
        if ($min > $max) {
            throw new \OutOfRangeException(sprintf(
                '$min (`%d`) must be less than or equal $max (`%d`)',
                $min,
                $max,
            ));
        }

        return (int) round($min + ($max - $min) * (crc32($key) / self::UINT32_MAX));
    }
}
