<?php

namespace Mention\Kebab\Clock\Exception;

class IncompatibleSystemException extends \Exception
{
    private function __construct(int $system)
    {
        parent::__construct(sprintf(
            'Method can only be used with php %dbit versions',
            $system,
        ));
    }

    public static function expected64(): self
    {
        return new self(64);
    }
}
