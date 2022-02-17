<?php

namespace Mention\Kebab\Tests\Pcre;

use Mention\Kebab\Pcre\Exception\PcreException;
use Mention\Kebab\Pcre\PcreUtils;
use PHPUnit\Framework\TestCase;

class PcreUtilsTest extends TestCase
{
    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::match
     */
    public function testMatch(): void
    {
        self::assertTrue(PcreUtils::match('/bar/', 'foobarbaz'));
        self::assertFalse(PcreUtils::match('/doesnotexist/', 'foobarbaz'));
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::match
     */
    public function testMatchThrows(): void
    {
        $this->expectException(PcreException::class);

        PcreUtils::match('/foo/', 'foobarbaz', $matches, 0, 12);
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::matchAll
     */
    public function testMatchAll(): void
    {
        self::assertEquals(1, PcreUtils::matchAll('/bar/', 'foobarbaz'));
        self::assertEquals(3, PcreUtils::matchAll('/bar/', 'barbarbar'));
        self::assertEquals(0, PcreUtils::matchAll('/foo/', 'barbarbar'));
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::matchAll
     */
    public function testMatchAllThrows(): void
    {
        $this->expectException(PcreException::class);

        PcreUtils::matchAll('/foo/', 'foobarbaz', $matches, 0, 12);
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::replace
     */
    public function testReplace(): void
    {
        self::assertEquals(
            'foofoobaz',
            PcreUtils::replace('/bar/', 'foo', 'foobarbaz')
        );
        self::assertEquals(
            'foobarbaz',
            PcreUtils::replace('/notmatching/', 'foo', 'foobarbaz')
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::replaceArray
     */
    public function testReplaceArray(): void
    {
        self::assertEquals(
            'foofoobaz',
            PcreUtils::replaceArray(['/bar/' => 'foo'], 'foobarbaz')
        );
        self::assertEquals(
            'foobarbaz',
            PcreUtils::replaceArray(['/notmatching/' => 'foo'], 'foobarbaz')
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::replaceMultiple
     */
    public function testReplaceMultiple(): void
    {
        self::assertEquals(
            ['bar', 'bar', 'bar'],
            PcreUtils::replaceMultiple('/foo/', 'bar', ['foo', 'foo', 'foo'])
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::replaceArrayMultiple
     */
    public function testReplaceArrayMultiple(): void
    {
        self::assertEquals(
            ['bar', 'bar', 'bar'],
            PcreUtils::replaceArrayMultiple(['/foo/' => 'bar'], ['foo', 'foo', 'foo'])
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::replaceCallback
     */
    public function testReplaceCallback(): void
    {
        $callback = function (array $matches): string {
            return (string) ((int) $matches[0] + 1);
        };
        self::assertEquals(
            '2344',
            PcreUtils::replaceCallback('/\d/', $callback, '1234', 3, $count)
        );
        self::assertEquals(3, $count);
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::replaceCallbackMultiple
     */
    public function testReplaceCallbackMultiple(): void
    {
        $callback = function (array $matches): string {
            return (string) ((int) $matches[0] * 10);
        };
        self::assertEquals(
            ['10', '20', '30'],
            PcreUtils::replaceCallbackMultiple('/\d/', $callback, ['1', '2', '3'])
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::replaceCallbackArray
     */
    public function testReplaceCallbackArray(): void
    {
        self::assertEquals(
            '2!3!4!5',
            PcreUtils::replaceCallbackArray(
                [
                    '/\d/' => function (array $matches): string {
                        return (string) ((int) $matches[0] + 1);
                    },
                    '/\s/' => function (array $matches): string {
                        return '!';
                    },

                ],
                '1 2 3 4'
            )
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::replaceCallbackArrayMultiple
     */
    public function testReplaceCallbackArrayMultiple(): void
    {
        self::assertEquals(
            ['1!', '!0', '3', '!'],
            PcreUtils::replaceCallbackArrayMultiple(
                [
                    '/\d/' => function (array $matches): string {
                        return (int) $matches[0] % 2 === 0
                            ? '0'
                            : $matches[0];
                    },
                    '/\s/' => function (array $matches): string {
                        return '!';
                    },

                ],
                ['1 ', ' 2', '3', ' ']
            )
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::filter
     */
    public function testFilter(): void
    {
        self::assertEquals(
            'foofoobaz',
            PcreUtils::filter('/bar/', 'foo', 'foobarbaz')
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::filterArray
     */
    public function testFilterArray(): void
    {
        self::assertEquals(
            'foofoobaz',
            PcreUtils::filterArray(['/bar/' => 'foo'], 'foobarbaz')
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::filter
     */
    public function testFilterThrows(): void
    {
        $this->expectException(PcreException::class);

        PcreUtils::filter('/notmatching/', 'foo', 'foobarbaz');
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::filterMultiple
     */
    public function testFilterMultiple(): void
    {
        self::assertEquals(
            ['bar', 'bar', 'bar'],
            PcreUtils::filterMultiple('/foo/', 'bar', ['foo', 'foo', 'foo'])
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::filterArrayMultiple
     */
    public function testFilterArrayMultiple(): void
    {
        self::assertEquals(
            ['bar', 'bar', 'bar'],
            PcreUtils::filterArrayMultiple(['/foo/' => 'bar'], ['foo', 'foo', 'foo'])
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::split
     */
    public function testSplit(): void
    {
        self::assertEquals(
            ['foo', 'bar', 'baz'],
            PcreUtils::split('/\d/', 'foo1bar2baz')
        );
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::quote
     */
    public function testQuote(): void
    {
        self::assertEquals('foo\$bar', PcreUtils::quote('foo$bar'));
    }

    /**
     * @covers \Mention\Kebab\Pcre\PcreUtils::::grep
     */
    public function testGrep(): void
    {
        self::assertEquals(
            [0 => 'foo', 2 => 'bar', 4 => 'baz'],
            PcreUtils::grep('/[a-z]+/', ['foo', '12', 'bar', ' ', 'baz'])
        );
    }
}
