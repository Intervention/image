<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\NotWritableException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\FileInterface;
use Intervention\Image\Traits\CanBuildFilePointer;

class File implements FileInterface
{
    use CanBuildFilePointer;

    /**
     * @var resource
     */
    protected $pointer;

    /**
     * Create new instance
     *
     * @param string|resource|null $data
     * @throws RuntimeException
     */
    public function __construct(mixed $data = null)
    {
        $this->pointer = $this->buildFilePointer($data);
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

        if (is_file($filepath) && !is_writable($filepath)) {
            throw new NotWritableException(
                "Can't write image. Path ({$filepath}) is not writable."
            );
        }

        // write data
        $saved = @file_put_contents($filepath, $this->pointer);
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
        return stream_get_contents($this->pointer, offset: 0);
    }

    /**
     * {@inheritdoc}
     *
     * @see FilterInterface::toFilePointer()
     */
    public function toFilePointer()
    {
        rewind($this->pointer);

        return $this->pointer;
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::size()
     */
    public function size(): int
    {
        $info = fstat($this->pointer);

        return intval($info['size']);
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
