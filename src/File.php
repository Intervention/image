<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Exceptions\NotWritableException;
use Intervention\Image\Interfaces\FileInterface;
use Intervention\Image\Traits\CanBuildFilePointer;

class File implements FileInterface
{
    use CanBuildFilePointer;

    /**
     * Data stream
     *
     * @var resource $stream
     */
    protected mixed $stream;

    /**
     * Create new instance
     *
     * @param mixed $data
     * @throws InputException
     * @return void
     */
    public function __construct(mixed $data)
    {
        $this->stream = match (true) {
            is_resource($data) => $data,
            is_string($data) => $this->buildFilePointer($data),
            default => throw new InputException('Argument #1 ($data) must be of type string or resource.'),
        };
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

        // create output file pointer
        if (!$output = fopen($filepath, 'a')) {
            throw new NotWritableException(
                "Can't write image to path. File path is not writable."
            );
        }

        // create source file pointer
        $source = $this->toFilePointer();

        while (!feof($source)) {
            $buffer = fread($source, 8192);

            if ($buffer === false) {
                throw new NotWritableException(
                    "Can't write image to path. Unable to read source."
                );
            }

            // write buffer to output
            fwrite($output, $buffer);
        }

        fclose($output);
    }

    /**
     * {@inheritdoc}
     *
     * @see FilterInterface::toString()
     */
    public function toString(): string
    {
        rewind($this->stream);

        return stream_get_contents($this->stream);
    }

    /**
     * {@inheritdoc}
     *
     * @see FilterInterface::toFilePointer()
     */
    public function toFilePointer()
    {
        rewind($this->stream);

        return $this->stream;
    }

    /**
     * {@inheritdoc}
     *
     * @see FileInterface::size()
     */
    public function size(): int
    {
        return fstat($this->stream)['size'];
    }

    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::mediaType()
     */
    public function mediaType(): string
    {
        $detected = mime_content_type($this->stream);

        if ($detected === false) {
            return 'application/x-empty';
        }

        return $detected;
    }

    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::mimetype()
     */
    public function mimetype(): string
    {
        return $this->mediaType();
    }

    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::toDataUri()
     */
    public function toDataUri(): string
    {
        return sprintf('data:%s;base64,%s', $this->mediaType(), base64_encode((string) $this));
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
