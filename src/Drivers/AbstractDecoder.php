<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Exception;
use Intervention\Image\Collection;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Traits\CanBuildFilePointer;

abstract class AbstractDecoder implements DecoderInterface
{
    use CanBuildFilePointer;

    /**
     * Determine if the given input is GIF data format
     */
    protected function isGifFormat(string $input): bool
    {
        return str_starts_with($input, 'GIF87a') || str_starts_with($input, 'GIF89a');
    }

    /**
     * Determine if given input is a path to an existing regular file
     */
    protected function isFile(mixed $input): bool
    {
        if (!is_string($input)) {
            return false;
        }

        if (strlen($input) > PHP_MAXPATHLEN) {
            return false;
        }

        try {
            if (!@is_file($input)) {
                return false;
            }
        } catch (Exception) {
            return false;
        }

        return true;
    }

    /**
     * Extract and return EXIF data from given input which can be binary image
     * data or a file path.
     *
     * @return CollectionInterface<string, mixed>
     */
    protected function extractExifData(string $path_or_data): CollectionInterface
    {
        if (!function_exists('exif_read_data')) {
            return new Collection();
        }

        try {
            $source = match (true) {
                $this->isFile($path_or_data) => $path_or_data, // path
                default => $this->buildFilePointer($path_or_data), // data
            };

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
     * Determine if given input is base64 encoded data
     */
    protected function isValidBase64(mixed $input): bool
    {
        if (!is_string($input)) {
            return false;
        }

        return base64_encode(base64_decode($input)) === str_replace(["\n", "\r"], '', $input);
    }

    /**
     * Parse data uri
     */
    protected function parseDataUri(mixed $input): object
    {
        $pattern = "/^data:(?P<mediatype>\w+\/[-+.\w]+)?" .
            "(?P<parameters>(;[-\w]+=[-\w]+)*)(?P<base64>;base64)?,(?P<data>.*)/";

        $result = preg_match($pattern, (string) $input, $matches);

        return new class ($matches, $result)
        {
            /**
             * @param array<mixed> $matches
             * @return void
             */
            public function __construct(private array $matches, private int|false $result)
            {
                //
            }

            public function isValid(): bool
            {
                return (bool) $this->result;
            }

            public function mediaType(): ?string
            {
                if (isset($this->matches['mediatype']) && !empty($this->matches['mediatype'])) {
                    return $this->matches['mediatype'];
                }

                return null;
            }

            public function hasMediaType(): bool
            {
                return !empty($this->mediaType());
            }

            public function isBase64Encoded(): bool
            {
                return isset($this->matches['base64']) && $this->matches['base64'] === ';base64';
            }

            public function data(): ?string
            {
                if (isset($this->matches['data']) && !empty($this->matches['data'])) {
                    return $this->matches['data'];
                }

                return null;
            }
        };
    }
}
