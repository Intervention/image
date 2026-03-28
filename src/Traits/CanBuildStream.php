<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\InvalidArgumentException;

trait CanBuildStream
{
    /**
     * Transform the provided data into a stream resource with the data as its content.
     *
     * @param resource|string|null $data
     * @throws InvalidArgumentException
     * @throws StreamException
     * @return resource
     */
    public static function buildStreamOrFail(mixed $data = null)
    {
        $buildStrategy = match (true) {
            is_null($data) => fn(mixed $data) => fopen('php://temp', 'r+'),
            is_resource($data) && get_resource_type($data) === 'stream' => fn(mixed $data) => $data,
            is_string($data) => function (mixed $data) {
                $stream = fopen('php://temp', 'r+');

                if ($stream === false) {
                    throw new StreamException('Failed to build stream from string');
                }

                fwrite($stream, $data);
                return $stream;
            },
            default => throw new InvalidArgumentException(
                'Unable to create stream from ' . gettype($data) . '. Use only null, string or resource.',
            ),
        };

        $stream = $buildStrategy($data);

        if ($stream === false) {
            throw new StreamException('Failed to build stream');
        }

        $rewind = rewind($stream);

        if ($rewind === false) {
            throw new StreamException('Failed to rewind stream');
        }

        return $stream;
    }
}
