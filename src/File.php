<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\FileNotWritableException;
use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\FileInterface;
use Intervention\Image\Traits\CanBuildStream;
use Intervention\Image\Traits\CanParseFilePath;
use Stringable;

class File implements FileInterface, Stringable
{
    use CanBuildStream;
    use CanParseFilePath;

    /**
     * @var resource
     */
    protected $stream;

    /**
     * Create new instance.
     *
     * @param string|resource|null $data
     * @throws InvalidArgumentException
     * @throws StreamException
     */
    public function __construct(mixed $data = null)
    {
        $this->stream = $this->buildStreamOrFail($data);
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::fromPath()
     *
     * @throws InvalidArgumentException
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws StreamException
     */
    public static function fromPath(string $path): self
    {
        $stream = fopen(self::readableFilePathOrFail($path), 'r');

        if ($stream === false) {
            throw new FileNotReadableException('Failed to open file from path "' . $path . '"');
        }

        return new self($stream);
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::save()
     *
     * @throws InvalidArgumentException
     * @throws DirectoryNotFoundException
     * @throws FileNotWritableException
     * @throws StreamException
     */
    public function save(string $path): void
    {
        if ($path === '') {
            throw new InvalidArgumentException('Path must not be an empty string');
        }

        if (strlen($path) > PHP_MAXPATHLEN) {
            throw new InvalidArgumentException(
                "Path is longer than the configured max. value of " . PHP_MAXPATHLEN
            );
        }

        $dir = pathinfo($path, PATHINFO_DIRNAME);

        if (!is_dir($dir)) {
            throw new DirectoryNotFoundException(
                'Can\'t write to path. Directory "' . $dir . '" does not exist'
            );
        }

        if (!is_writable($dir)) {
            throw new FileNotWritableException(
                'Can\'t write to path. Directory "' . $dir . '" is not writable'
            );
        }

        if (is_file($path) && !is_writable($path)) {
            throw new FileNotWritableException(
                "Can't write to path. Existing file " . $path . " is not writable"
            );
        }

        // write data
        $saved = file_put_contents($path, $this->toStream());

        if ($saved === false) {
            throw new FileNotWritableException(
                "Failed to write file to path " . $path
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::toString()
     *
     * @throws StreamException
     */
    public function toString(): string
    {
        $data = stream_get_contents($this->toStream(), offset: 0);

        if ($data === false) {
            throw new StreamException('Unable to read data from stream');
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::toStream()
     *
     * @throws StreamException
     */
    public function toStream()
    {
        $rewind = rewind($this->stream);

        if ($rewind === false) {
            throw new StreamException('Failed to rewind stream');
        }

        return $this->stream;
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::size()
     *
     * @throws StreamException
     */
    public function size(): int
    {
        $info = fstat($this->toStream());

        if (!is_array($info)) {
            throw new StreamException('Unable to read size of stream');
        }

        return intval($info['size']);
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::__toString()
     *
     * @throws StreamException
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
