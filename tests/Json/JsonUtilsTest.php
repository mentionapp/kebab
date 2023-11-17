<?php

namespace Mention\Kebab\Tests\Json;

use Mention\Kebab\Json\Exception\JsonUtilsDecodeException;
use Mention\Kebab\Json\Exception\JsonUtilsEncodeException;
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

    public function testEncodeFailure(): void
    {
        $this->expectException(JsonUtilsEncodeException::class);
        $this->expectExceptionMessage('Failed encoding JSON');

        $data = "\xe9";
        JsonUtils::encode($data);
    }

    public function testDecodeObject(): void
    {
        $json = '{"hello":"world"}';
        $data = JsonUtils::decodeObject($json);

        self::assertEquals((object) ['hello' => 'world'], $data);
    }

    public function testDecodeObjectFailure(): void
    {
        $this->expectException(JsonUtilsDecodeException::class);
        $this->expectExceptionMessage('Failed decoding JSON');

        $json = '"hello';
        JsonUtils::decodeObject($json);
    }

    public function testDecodeArray(): void
    {
        $json = '{"hello":"world"}';
        $data = JsonUtils::decodeArray($json);

        self::assertEquals(['hello' => 'world'], $data);
    }

    public function testDecodeArrayFailure(): void
    {
        $this->expectException(JsonUtilsDecodeException::class);
        $this->expectExceptionMessage('Failed decoding JSON');

        $json = '"hello';
        JsonUtils::decodeArray($json);
    }

    public function testIsValidJson(): void
    {
        $json = '{"hello":"world"}';
        self::assertTrue(JsonUtils::isValidJson($json));
    }

    public function testIsValidJsonFailure(): void
    {
        $json = '';
        self::assertFalse(JsonUtils::isValidJson($json));
    }
}
