<?php

namespace Mention\Kebab\Json\Exception;

use Mention\Kebab\Log\LogUtils;

class JsonUtilsDecodeException extends JsonUtilsException
{
    public function __construct(string $value, string $error)
    {
        $value = LogUtils::truncate($value);

        $msg = sprintf('Failed decoding JSON: %s: %s', $error, $value);

        parent::__construct($msg);
    }
}
