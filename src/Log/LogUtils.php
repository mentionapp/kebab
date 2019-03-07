<?php

namespace Mention\Kebab\Log;

class LogUtils
{
    /**
     * Truncates long strings for logging purposes.
     */
    public static function truncate(string $str, int $length = 255): string
    {
        if (strlen($str) <= $length) {
            return $str;
        }

        if ((bool) getenv('NO_TRUNCATE_LOG')) {
            return $str;
        }

        return sprintf(
            '%s ... (truncated: %s bytes total. Set NO_TRUNCATE_LOG=1 to disable.)',
            substr($str, 0, $length),
            strlen($str)
        );
    }
}
