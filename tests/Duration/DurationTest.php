<?php

namespace Mention\Kebab\Tests\Duration;

use Mention\Kebab\Duration\Duration;
use PHPUnit\Framework\TestCase;

final class DurationTest extends TestCase
{
    public function testDurationToInt(): void
    {
        $duration = Duration::days(3);

        self::assertSame(3, $duration->toDaysInt());
        self::assertSame(3*24, $duration->toHoursInt());
        self::assertSame(3*24*60, $duration->toMinutesInt());
        self::assertSame(3*24*60*60, $duration->toSecondsInt());
        self::assertSame(3*24*60*60*1_000, $duration->toMilliSecondsInt());
        self::assertSame(3*24*60*60*1_000*1_000, $duration->toMicroSecondsInt());
        self::assertSame(3*24*60*60*1_000*1_000*1_000, $duration->toNanoSecondsInt());
    }

    public function testDurationConstructors(): void
    {
        $duration = Duration::nanoSecond();
        self::assertEqualsWithDelta(1e-9, $duration->toSeconds(), 1e-20);

        $duration = Duration::nanoSeconds(3);
        self::assertEqualsWithDelta(3*1e-9, $duration->toSeconds(), 1e-20);

        $duration = Duration::microSecond();
        self::assertEqualsWithDelta(1e-6, $duration->toSeconds(), 1e-20);

        $duration = Duration::microSeconds(3);
        self::assertEqualsWithDelta(3*1e-6, $duration->toSeconds(), 1e-20);

        $duration = Duration::milliSecond();
        self::assertEqualsWithDelta(1e-3, $duration->toSeconds(), 1e-20);

        $duration = Duration::milliSeconds(3);
        self::assertEqualsWithDelta(3*1e-3, $duration->toSeconds(), 1e-20);

        $duration = Duration::second();
        self::assertEqualsWithDelta(1, $duration->toSeconds(), 1e-20);

        $duration = Duration::seconds(3);
        self::assertEqualsWithDelta(3*1, $duration->toSeconds(), 1e-20);

        $duration = Duration::minute();
        self::assertEqualsWithDelta(60, $duration->toSeconds(), 1e-20);

        $duration = Duration::minutes(3);
        self::assertEqualsWithDelta(3*60, $duration->toSeconds(), 1e-20);

        $duration = Duration::hour();
        self::assertEqualsWithDelta(60*60, $duration->toSeconds(), 1e-20);

        $duration = Duration::hours(3);
        self::assertEqualsWithDelta(3*60*60, $duration->toSeconds(), 1e-20);

        $duration = Duration::day();
        self::assertEqualsWithDelta(24*60*60, $duration->toSeconds(), 1e-20);

        $duration = Duration::days(3);
        self::assertEqualsWithDelta(3*24*60*60, $duration->toSeconds(), 1e-20);
    }

    public function testDurationArithmetic(): void
    {
        $duration = Duration::second()->mul(86_400);
        self::assertEqualsWithDelta(
            Duration::day()->toSeconds(),
            $duration->toSeconds(),
            1e-20,
        );

        $duration = Duration::second()->div(1_000);
        self::assertEqualsWithDelta(
            Duration::milliSecond()->toMilliSeconds(),
            $duration->toMilliSeconds(),
            1e-20,
        );

        $duration = Duration::second()->add(Duration::milliSeconds(234));
        self::assertSame(1_234, $duration->toMilliSecondsInt());

        $duration = Duration::second()->sub(Duration::milliSeconds(234));
        self::assertSame(766, $duration->toMilliSecondsInt());
    }

    /** @dataProvider getTestMulDetectsOverflowsData */
    public function testMulDetectsOverflows(
        bool $expectException,
        int $nanoSeconds,
        int $factor,
    ): void {
        if ($expectException) {
            self::expectException(\OverflowException::class);
        }

        Duration::nanoSeconds($nanoSeconds)->mul($factor);

        self::expectNotToPerformAssertions();
    }

    /** @return array<array{bool,int,int}> */
    public function getTestMulDetectsOverflowsData(): array
    {
        return [
            'simple' => [false, 1, 1],
            'limit' => [false, 1, PHP_INT_MAX],
            'overflow' => [true, 2, PHP_INT_MAX],
            'overflow 2' => [true, PHP_INT_MAX, 2],
            'underflow' => [true, -2, PHP_INT_MAX],
            'underflow 2' => [true, -2, -PHP_INT_MAX],
            'limit 2' => [false, -1, PHP_INT_MAX],
            'limit 3' => [false, 1, PHP_INT_MIN],
            'underflow 3' => [true, -1, PHP_INT_MIN],
        ];
    }
}
