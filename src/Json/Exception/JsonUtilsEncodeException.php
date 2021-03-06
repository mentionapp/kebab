<?php

namespace Mention\Kebab\Json\Exception;

use Mention\Kebab\Log\LogUtils;

class JsonUtilsEncodeException extends JsonUtilsException
{
    /** @param mixed $value */
    public function __construct($value, string $error)
    {
        $valueStr = var_export($value, true);
        $valueStr = LogUtils::truncate($valueStr);

        $msg = sprintf('Failed encoding JSON: %s: %s', $error, $valueStr);

        parent::__construct($msg);
    }
}
