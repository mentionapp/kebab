<?php

namespace Mention\PhpUtils\Json\Exception;

use Mention\PhpUtils\Log\LogUtils;

class JsonUtilsDecodeException extends JsonUtilsException
{
    public function __construct(string $value, string $error)
    {
        $value = LogUtils::truncate($value);

        $msg = sprintf('Failed decoding JSON: %s: %s', $error, $value);

        parent::__construct($msg);
    }
}
