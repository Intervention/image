<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Exception;
use Intervention\Image\Collection;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Traits\CanBuildFilePointer;
use Intervention\Image\Traits\CanParseFilePath;
use Stringable;
use Throwable;

abstract class AbstractDecoder implements DecoderInterface
{
    use CanBuildFilePointer;
    use CanParseFilePath;

    /**
     * Determine if the given input is GIF data format
     */
    protected function isGifFormat(string $input): bool
    {
        return str_starts_with($input, 'GIF87a') || str_starts_with($input, 'GIF89a');
    }

    /**
     * Extract and return EXIF data from given input which can be a file path
     * or a file pointer stream resource.
     *
     * @return CollectionInterface<string, mixed>
     */
    protected function extractExifData(string $input): CollectionInterface
    {
        if (!function_exists('exif_read_data')) {
            return new Collection();
        }

        try {
            // source might be file path
            $source = $this->parseFilePathOrFail($input);
        } catch (Throwable) {
            try {
                // source might be file pointer
                $source = $this->buildFilePointerOrFail($input);
            } catch (RuntimeException) {
                return new Collection();
            }
        }

        try {
            // extract exif data
            $data = @exif_read_data($source, null, true);
            if (is_resource($source)) {
                fclose($source);
            }
        } catch (Exception) {
            $data = [];
        }

        return new Collection(is_array($data) ? $data : []);
    }

    /**
     * Decodes given base64 encoded data
     */
    protected function decodeBase64Data(mixed $input): string
    {
        if (!is_string($input) && !($input instanceof Stringable)) {
            throw new InvalidArgumentException(
                'Base64-encoded data must be either of type string or instance of Stringable',
            );
        }

        $decoded = base64_decode((string) $input, true);

        if ($decoded === false) {
            throw new DecoderException('Input is not valid Base64-encoded data');
        }

        if (base64_encode($decoded) !== str_replace(["\n", "\r"], '', (string) $input)) {
            throw new DecoderException('Input is not valid Base64-encoded data');
        }

        return $decoded;
    }
}
