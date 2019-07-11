<?php

namespace Mention\Kebab\Pcre\Exception;

class PcreException extends \Exception
{
    public function __construct(int $lastError)
    {
        parent::__construct(self::getLastErrorMessage($lastError), $lastError);
    }

    public static function fromLastError(): self
    {
        return new self(preg_last_error());
    }

    private static function getLastErrorMessage(int $lastError): string
    {
        switch ($lastError) {
            case PREG_INTERNAL_ERROR:
                return 'Internal error';
            case PREG_BACKTRACK_LIMIT_ERROR:
                return 'Backtrack limit error';
            case PREG_RECURSION_LIMIT_ERROR:
                return 'Recursion limit error';
            case PREG_BAD_UTF8_ERROR:
                return 'Bad UTF8 error';
            case PREG_BAD_UTF8_OFFSET_ERROR:
                return 'Bad UTF8 offset error';
            case PREG_JIT_STACKLIMIT_ERROR:
                return 'Jit stacklimit error';
            case PREG_NO_ERROR:
            default:
                return 'Unknown error';
        }
    }
}
