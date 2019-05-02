<?php

namespace Mention\Kebab\Tests\Json;

use Mention\Kebab\File\Exception\FileUtilsWriteTypeException;
use Mention\Kebab\File\FileUtils;
use PHPUnit\Framework\TestCase;

class FileUtilsTest extends TestCase
{
    public function testCorrectDataOnWrite()
    {
        $content = 'hello';
        $filename = './kebab-foobartest.txt';

        $writtenByte = FileUtils::write($filename, $content);

        self::assertEquals($writtenByte, strlen($content));

        $data = FileUtils::read($filename);

        self::assertEquals($content, $data);

        unlink($filename);
    }

    /** @dataProvider incorrectDataProvider */
    public function testIncorrectDataOnWrite($content)
    {
        self::expectException(FileUtilsWriteTypeException::class);

        FileUtils::write('./foobar.txt', $content);
    }

    public function incorrectDataProvider(): iterable
    {
        yield [
            new \StdClass()
        ];

        yield [
            42
        ];

        yield [
            42.42
        ];

        yield [
            \xml_parser_create()
        ];
    }
}
