<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\NotWritableException;
use Intervention\Image\Interfaces\FileInterface;
use Intervention\Image\Traits\CanBuildFilePointer;

class File implements FileInterface
{
    use CanBuildFilePointer;

    /**
     * Create new instance
     *
     * @param string $data
     */
    public function __construct(protected string $data)
    {
    }

    /**
     * Save encoded image data in file system
     *
     * @codeCoverageIgnore
     * @param string $filepath
     * @return void
     */
    public function save(string $filepath): void
    {
        $dir = pathinfo($filepath, PATHINFO_DIRNAME);

        if (!is_dir($dir)) {
            throw new NotWritableException(
                "Can't write image to path. Directory does not exist."
            );
        }

        if (!is_writable($dir)) {
            throw new NotWritableException(
                "Can't write image to path. Directory is not writable."
            );
        }

        // write data
        $saved = @file_put_contents($filepath, (string) $this);
        if ($saved === false) {
            throw new NotWritableException(
                "Can't write image data to path ({$filepath})."
            );
        }
    }

    /**
     * Cast encoded image object to string
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->data;
    }

    /**
     * Create file pointer from encoded image
     *
     * @return resource
     */
    public function toFilePointer()
    {
        return $this->buildFilePointer($this->toString());
    }

    /**
     * Return byte size of encoded image
     *
     * @return int
     */
    public function size(): int
    {
        return mb_strlen($this->data);
    }

    /**
     * Cast encoded image object to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
