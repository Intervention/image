<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\NotWritableException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\FileInterface;
use Intervention\Image\Traits\CanBuildFilePointer;
use Stringable;

class File implements FileInterface, Stringable
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
     * Create file object from path in file system
     *
     * @throws RuntimeException
     */
    public static function fromPath(string $path): self
    {
        return new self(fopen($path, 'r'));
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
                sprintf("Can't write image. Path (%s) is not writable.", $filepath)
            );
        }

        // write data
        $saved = @file_put_contents($filepath, $this->toFilePointer());
        if ($saved === false) {
            throw new NotWritableException(
                sprintf("Can't write image data to path (%s).", $filepath)
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::toString()
     */
    public function toString(): string
    {
        return stream_get_contents($this->toFilePointer(), offset: 0);
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::toFilePointer()
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
        $info = fstat($this->toFilePointer());

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
