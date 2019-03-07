<?php

namespace Mention\Kebab\Tests\Json;

use Mention\Kebab\Json\JsonUtils;
use PHPUnit\Framework\TestCase;

class JsonUtilsTest extends TestCase
{
    public function testEncode(): void
    {
        $data = ['hello'];
        $json = JsonUtils::encode($data);

        self::assertEquals('["hello"]', $json);
    }

    /**
     * @expectedException \Mention\Kebab\Json\Exception\JsonUtilsEncodeException
     * @expectedExceptionMessage Failed encoding JSON
     */
    public function testEncodeFailure(): void
    {
        $data = "\xe9";
        JsonUtils::encode($data);
    }

    public function testDecodeObject(): void
    {
        $json = '{"hello":"world"}';
        $data = JsonUtils::decodeObject($json);

        self::assertEquals((object) ['hello' => 'world'], $data);
    }

    /**
     * @expectedException \Mention\Kebab\Json\Exception\JsonUtilsDecodeException
     * @expectedExceptionMessage Failed decoding JSON
     */
    public function testDecodeObjectFailure(): void
    {
        $json = '"hello';
        JsonUtils::decodeObject($json);
    }

    public function testDecodeArray(): void
    {
        $json = '{"hello":"world"}';
        $data = JsonUtils::decodeArray($json);

        self::assertEquals(['hello' => 'world'], $data);
    }

    /**
     * @expectedException \Mention\Kebab\Json\Exception\JsonUtilsDecodeException
     * @expectedExceptionMessage Failed decoding JSON
     */
    public function testDecodeArrayFailure(): void
    {
        $json = '"hello';
        JsonUtils::decodeArray($json);
    }
}
