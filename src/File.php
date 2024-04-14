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
     * {@inheritdoc}
     *
     * @see FileInterface::save()
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
     * {@inheritdoc}
     *
     * @see FilterInterface::toString()
     */
    public function toString(): string
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     *
     * @see FilterInterface::toFilePointer()
     */
    public function toFilePointer()
    {
        return $this->buildFilePointer($this->toString());
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::size()
     */
    public function size(): int
    {
        return mb_strlen($this->data);
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::__toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
