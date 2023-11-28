<?php

use PHPUnit\Framework\TestCase;
use Mention\Kebab\Jitter\Jitter;

class JitterTest extends TestCase
{
    public function testRandomWithValidInput(): void
    {
        $value = 100;
        $factor = 0.5;

        $result = Jitter::random($value, $factor);

        self::assertGreaterThanOrEqual($value * (1 - $factor), $result);
        self::assertLessThanOrEqual($value * (1 + $factor), $result);
    }

    public function testRandomWithInvalidFactor(): void
    {
        self::expectException(\OutOfRangeException::class);
        Jitter::random(100, 1.5);
    }

    public function testStableWithValidInput(): void
    {
        $value = 100;
        $key = 'test';
        $factor = 0.5;

        $result = Jitter::stable($value, $key, $factor);

        self::assertGreaterThanOrEqual($value * (1 - $factor), $result);
        self::assertLessThanOrEqual($value * (1 + $factor), $result);

        $result2 = Jitter::stable($value, $key, $factor);

        self::assertEquals($result, $result2);
    }

    public function testStableWithInvalidFactor(): void
    {
        self::expectException(\OutOfRangeException::class);
        Jitter::stable(100, 'test', -0.1);
    }

    public function testRangeStableWithValidInput(): void
    {
        $min = 50;
        $max = 150;
        $key = 'test';

        $result = Jitter::rangeStable($min, $max, $key);

        self::assertGreaterThanOrEqual($min, $result);
        self::assertLessThanOrEqual($max, $result);

        $result2 = Jitter::rangeStable($min, $max, $key);

        self::assertEquals($result, $result2);
    }

    public function testRangeStableWithInvalidRange(): void
    {
        self::expectException(\OutOfRangeException::class);
        Jitter::rangeStable(150, 50, 'test');
    }
}
