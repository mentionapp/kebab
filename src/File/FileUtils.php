<?php

namespace Mention\Kebab\File;

use Mention\Kebab\File\Exception\FileUtilsOpenException;
use Mention\Kebab\File\Exception\FileUtilsReadException;
use Mention\Kebab\File\Exception\FileUtilsWriteException;
use Mention\Kebab\File\Exception\FileUtilsWriteTypeException;

class FileUtils
{
    /**
     * Returns the content of filename.
     *
     * @throws FileUtilsReadException on failure
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
     * @throws FileUtilsOpenException
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

    /**
     * Write data into filename.
     *
     * @param string|array|resource $data The data to write.
     *
     * @throws FileUtilsWriteTypeException on wrong data type
     * @throws FileUtilsWriteException on failure
     */
    public static function write(
        string $filename,
        $data,
        int $flag = 0
    ): int {
        if (!is_string($data) &&
            !is_array($data) &&
            !is_resource($data)
        ) {
            throw new FileUtilsWriteTypeException(sprintf(
                'Data type `%s` is not supported.',
                gettype($data)
            ));
        }

        if (is_resource($data) && get_resource_type($data) !== 'stream') {
            throw new FileUtilsWriteTypeException(sprintf(
                'Data resource type can only be `stream`, %s found.',
                get_resource_type($data)
            ));
        }

        $writtenByte = file_put_contents($filename, $data, $flag);

        if (false === $data) {
            throw new FileUtilsWriteException(sprintf(
                'Failed writing `%s`',
                $filename
            ));
        }

        return $writtenByte;
    }
}
