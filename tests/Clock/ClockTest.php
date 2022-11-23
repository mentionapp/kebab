<?php

namespace Mention\Kebab\Tests\Clock;

use Mention\Kebab\Clock\Clock;
use Mention\Kebab\Clock\Exception\IncompatibleSystemException;
use PHPUnit\Framework\TestCase;

class ClockTest extends TestCase
{
    public function testHrtimeArray(): void
    {
        Clock::enableMocking(0.0); // Let's travel through time!
        $start = Clock::hrtimeArray();
        $new = Clock::hrtimeArray();
        self::assertEquals($start[0], $new[0]);
        self::assertEquals($start[1], $new[1]);

        // 1 second and 5 milliseconds to the future
        Clock::usleep(1005000);
        $new = Clock::hrtimeArray();
        self::assertEquals(1, $new[0] - $start[0]);
        // 5 milliseconds is 5M nanoseconds
        self::assertEquals(5 * 1e+6, $new[1] - $start[1]);
    }

    public function testHrtimeInt(): void
    {
        // Different tests depending on the php version executed
        switch (PHP_INT_SIZE) {
            case 8:
                Clock::enableMocking(0.0); // Let's travel through time!
                $start = Clock::hrtimeInt();
                $new = Clock::hrtimeInt();
                self::assertEquals($start, $new);
                self::assertEquals($start, $new);

                // 25 microseconds to the future
                Clock::usleep(25);
                $new = Clock::hrtimeInt();
                // 25 microseconds is 25k nanoseconds
                self::assertEquals(25 * 1e+3, $new - $start);

                break;
            case 4:
                $e = null;

                try {
                    Clock::hrtimeInt();
                } catch (IncompatibleSystemException $e) {
                }

                self::assertNotNull($e);

                break;
            default:
                throw new \Exception(sprintf(
                    'A specific test for php version using PHP_INT_SIZE of %d should be added',
                    PHP_INT_SIZE,
                ));
        }
    }

    public function testHrtimeFloat(): void
    {
        Clock::enableMocking(0.0); // Let's travel through time!
        $start = Clock::hrtimeFloat();
        $new = Clock::hrtimeFloat();
        self::assertEquals($start, $new);
        self::assertEquals($start, $new);

        // 25 microseconds to the future
        Clock::usleep(25);
        $new = Clock::hrtimeFloat();
        // 25 microseconds is 25k nanoseconds
        self::assertEquals(25 * 1e+3, $new - $start);
    }
}
