<?php

namespace Mention\PhpUtils\File;

use Mention\PhpUtils\File\Exception\FileUtilsOpenException;
use Mention\PhpUtils\File\Exception\FileUtilsReadException;

class FileUtils
{
    /**
     * Returns the content of filename.
     *
     * @throw FileUtilsReadException on failure
     */
    public static function read(string $filename): string
    {
        $data = file_get_contents($filename);

        if (false === $data) {
            throw new FileUtilsReadException(sprintf(
                'Failed reading `%s`',
                $filename
            ));
        }

        return $data;
    }

    /**
     * Opens filename.
     *
     * @return resource
     *
     * @throw FileUtilsOpenException
     */
    public static function open(string $filename, string $mode)
    {
        $fd = fopen($filename, $mode);

        if (false === $fd) {
            throw new FileUtilsOpenException(sprintf(
                'Failed opening `%s` in `%s` mode',
                $filename,
                $mode
            ));
        }

        return $fd;
    }
}
