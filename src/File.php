<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\FileNotWritableException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\FilesystemException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\FileInterface;
use Intervention\Image\Traits\CanBuildFilePointer;
use Intervention\Image\Traits\CanParseFilePath;
use Stringable;

class File implements FileInterface, Stringable
{
    use CanBuildFilePointer;
    use CanParseFilePath;

    /**
     * @var resource
     */
    protected $pointer;

    /**
     * Create new instance
     *
     * @param string|resource|null $data
     * @throws FilesystemException
     * @throws InvalidArgumentException
     */
    public function __construct(mixed $data = null)
    {
        $this->pointer = $this->buildFilePointerOrFail($data);
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::fromPath()
     *
     * @throws FilesystemException
     * @throws InvalidArgumentException
     */
    public static function fromPath(string $path): self
    {
        $pointer = fopen(self::parseFilePathOrFail($path), 'r');

        if ($pointer === false) {
            // NEWEX
            throw new FileNotReadableException('Failed to open file from path "' . $path . '"');
        }

        return new self($pointer);
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::save()
     * @throws FilesystemException
     * @throws InvalidArgumentException
     */
    public function save(string $path): void
    {
        if ($path === '') {
            // NEWEX
            throw new InvalidArgumentException('Path must not be an empty string');
        }

        if (strlen($path) > PHP_MAXPATHLEN) {
            // NEWEX
            throw new InvalidArgumentException(
                "Path is longer than the configured max. value of " . PHP_MAXPATHLEN
            );
        }

        $dir = pathinfo($path, PATHINFO_DIRNAME);

        if (!is_dir($dir)) {
            // NEWEX
            throw new DirectoryNotFoundException(
                "Can't write to path. Directory does not exist"
            );
        }

        if (!is_writable($dir)) {
            // NEWEX
            throw new FileNotWritableException(
                "Can't write to path. Directory is not writable"
            );
        }

        if (is_file($path) && !is_writable($path)) {
            // NEWEX
            throw new FileNotWritableException(
                "Can't write to path. Existing file " . $path . " is not writable"
            );
        }

        // write data
        $saved = @file_put_contents($path, $this->toFilePointer());

        if ($saved === false) {
            // NEWEX
            throw new FileNotWritableException(
                "Failed to write file to path " . $path
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::toString()
     * @throws FilePointerException
     */
    public function toString(): string
    {
        $data = stream_get_contents($this->toFilePointer(), offset: 0);

        if ($data === false) {
            // NEWEX
            throw new FileNotReadableException('Unable to read data from file pointer');
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::toFilePointer()
     *
     * @throws FilePointerException
     */
    public function toFilePointer()
    {
        $rewind = rewind($this->pointer);

        if ($rewind === false) {
            // NEWEX
            throw new FilePointerException('Failed to rewind file pointer');
        }

        return $this->pointer;
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::size()
     *
     * @throws FileNotReadableException
     */
    public function size(): int
    {
        $info = fstat($this->toFilePointer());

        if (!is_array($info)) {
            // NEWEX
            throw new FileNotReadableException('Unable to read size of file pointer');
        }

        return intval($info['size']);
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::__toString()
     *
     * @throws FilePointerException
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
