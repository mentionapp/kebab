<?php

namespace Mention\Kebab\Duration;

final class Duration
{
    private const NANO_SECOND = 1;

    private const MICRO_SECOND = self::NANO_SECOND * 1_000;

    private const MILLI_SECOND = self::MICRO_SECOND * 1_000;

    private const SECOND = self::MILLI_SECOND * 1_000;

    private const MINUTE = self::SECOND * 60;

    private const HOUR = self::MINUTE * 60;

    private const DAY = self::HOUR * 24;

    private const WEEK = self::DAY * 7;

    private function __construct(
        private readonly int $value,
    ) {
    }

    public static function nanoSecond(): self
    {
        return new self(self::NANO_SECOND);
    }

    public static function nanoSeconds(int $quantity): self
    {
        return new self(self::NANO_SECOND * $quantity);
    }

    public static function microSecond(): self
    {
        return new self(self::MICRO_SECOND);
    }

    public static function microSeconds(int $quantity): self
    {
        return new self(self::MICRO_SECOND * $quantity);
    }

    public static function milliSecond(): self
    {
        return new self(self::MILLI_SECOND);
    }

    public static function milliSeconds(int $quantity): self
    {
        return new self(self::MILLI_SECOND * $quantity);
    }

    public static function second(): self
    {
        return new self(self::SECOND);
    }

    public static function seconds(int $quantity): self
    {
        return new self(self::SECOND * $quantity);
    }

    public static function minute(): self
    {
        return new self(self::MINUTE);
    }

    public static function minutes(int $quantity): self
    {
        return new self(self::MINUTE * $quantity);
    }

    public static function hour(): self
    {
        return new self(self::HOUR);
    }

    public static function hours(int $quantity): self
    {
        return new self(self::HOUR * $quantity);
    }

    public static function day(): self
    {
        return new self(self::DAY);
    }

    public static function days(int $quantity): self
    {
        return new self(self::DAY * $quantity);
    }

    public static function week(): self
    {
        return new self(self::WEEK);
    }

    public static function weeks(int $quantity): self
    {
        return new self(self::WEEK * $quantity);
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public static function maxDuration(): self
    {
        return new self(PHP_INT_MAX);
    }

    /** @throws \OverflowException on overflow */
    public function mul(int|float $value): self
    {
        if ($this->mulOverflows($this->value, $value)) {
            throw new \OverflowException();
        }

        $newValue = $this->value * $value;

        assert((PHP_INT_MIN <= $newValue) && ($newValue <= PHP_INT_MAX));

        return new self(intval($newValue));
    }

    /** @param positive-int|negative-int|float $value */
    public function div(int|float $value): self
    {
        return new self(intval($this->value / $value));
    }

    public function add(self $other): self
    {
        return new self($this->value + $other->value);
    }

    public function sub(self $other): self
    {
        return new self($this->value - $other->value);
    }

    public function toNanoSeconds(): float
    {
        return $this->value / self::NANO_SECOND;
    }

    public function toNanoSecondsInt(): int
    {
        return intval($this->value / self::NANO_SECOND);
    }

    public function toMicroSeconds(): float
    {
        return $this->value / self::MICRO_SECOND;
    }

    public function toMicroSecondsInt(): int
    {
        return intval($this->value / self::MICRO_SECOND);
    }

    public function toMilliSeconds(): float
    {
        return $this->value / self::MILLI_SECOND;
    }

    public function toMilliSecondsInt(): int
    {
        return intval($this->value / self::MILLI_SECOND);
    }

    public function toSeconds(): float
    {
        return $this->value / self::SECOND;
    }

    public function toSecondsInt(): int
    {
        return intval($this->value / self::SECOND);
    }

    public function toMinutes(): float
    {
        return $this->value / self::MINUTE;
    }

    public function toMinutesInt(): int
    {
        return intval($this->value / self::MINUTE);
    }

    public function toHours(): float
    {
        return $this->value / self::HOUR;
    }

    public function toHoursInt(): int
    {
        return intval($this->value / self::HOUR);
    }

    public function toDays(): float
    {
        return $this->value / self::DAY;
    }

    public function toDaysInt(): int
    {
        return intval($this->value / self::DAY);
    }

    public function toWeeks(): float
    {
        return $this->value / self::WEEK;
    }

    public function toWeeksInt(): int
    {
        return intval($this->value / self::WEEK);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function greaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function lessThan(self $other): bool
    {
        return $this->value < $other->value;
    }

    public function max(self $other): self
    {
        return $this->value >= $other->value ? $this : $other;
    }

    public function min(self $other): self
    {
        return $this->value <= $other->value ? $this : $other;
    }

    private function mulOverflows(int|float $a, int|float $b): bool
    {
        if ($a > 0) {
            if ($b > 0) {
                return $a > PHP_INT_MAX / $b;
            }

            return $b < PHP_INT_MIN / $a;
        }

        if ($b > 0) {
            return $a < PHP_INT_MIN / $b;
        }

        return $a !== 0 && $b < PHP_INT_MAX / $a;
    }
}
