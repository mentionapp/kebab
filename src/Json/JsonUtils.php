<?php

namespace Mention\Kebab\Json;

use Mention\Kebab\Json\Exception\JsonUtilsDecodeException;
use Mention\Kebab\Json\Exception\JsonUtilsEncodeException;

class JsonUtils
{
    /**
     * Encodes a value to JSON.
     *
     * @param mixed $value
     *
     * @throws JsonUtilsEncodeException if the value can not be encoded
     */
    public static function encode($value, int $options = 0): string
    {
        $json = json_encode($value, $options);

        if (false === $json) {
            throw new JsonUtilsEncodeException($value, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Encodes a value to a pretty printed JSON string.
     *
     * @param mixed $value
     *
     * @throws JsonUtilsEncodeException if the value can not be encoded
     */
    public static function encodePretty($value): string
    {
        return self::encode($value, JSON_PRETTY_PRINT);
    }

    /**
     * Returns a pretty-printed JSON string.
     */
    public static function prettify(string $json): string
    {
        return self::encodePretty(self::decodeArray($json));
    }

    /**
     * Decodes a JSON string, prefer objects.
     *
     * JSON objects are decoded as PHP objects (stdClass instances).
     *
     * @throws JsonUtilsDecodeException if the value can not be decoded
     *
     * @return mixed
     */
    public static function decodeObject(string $json)
    {
        return self::decodeInternal($json, false);
    }

    /**
     * Decodes a JSON string as PHP arrays.
     *
     * JSON objects are decoded as PHP associative arrays.
     *
     * @throws JsonUtilsDecodeException if the value can not be decoded
     *
     * @return mixed
     */
    public static function decodeArray(string $json)
    {
        return self::decodeInternal($json, true);
    }

    /**
     * Returns value after encoding it and decoding it as JSON.
     *
     * @param mixed $value
     *
     * @throws JsonUtilsEncodeException if the value can not be encoded
     * @throws JsonUtilsDecodeException if the value can not be decoded
     *
     * @return mixed
     */
    public static function roundTrip($value)
    {
        return self::decodeArray(self::encode($value));
    }

    /** @return mixed */
    private static function decodeInternal(string $json, bool $assoc)
    {
        $value = json_decode($json, $assoc);
        if (null === $value && JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonUtilsDecodeException($json, json_last_error_msg());
        }

        return $value;
    }
}
