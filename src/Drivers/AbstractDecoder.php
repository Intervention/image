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
     *
     * @param string $input
     * @return bool
     */
    protected function isGifFormat(string $input): bool
    {
        return 1 === preg_match(
            "/^47494638(37|39)61/",
            strtoupper(substr(bin2hex($input), 0, 32))
        );
    }

    /**
     * Determine if given input is a path to an existing regular file
     *
     * @param mixed $input
     * @return bool
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
     * @param string $path_or_data
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
     *
     * @param mixed $input
     * @return bool
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
     *
     * @param mixed $input
     * @return object
     */
    protected function parseDataUri(mixed $input): object
    {
        $pattern = "/^data:(?P<mediatype>\w+\/[-+.\w]+)?" .
            "(?P<parameters>(;[-\w]+=[-\w]+)*)(?P<base64>;base64)?,(?P<data>.*)/";

        $result = preg_match($pattern, $input, $matches);

        return new class ($matches, $result)
        {
            /**
             * @var array<mixed>
             */
            private array $matches;
            private int|false $result;

            /**
             * @param array<mixed> $matches
             * @param int|false $result
             * @return void
             */
            public function __construct(array $matches, int|false $result)
            {
                $this->matches = $matches;
                $this->result = $result;
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
                if (isset($this->matches['base64']) && $this->matches['base64'] === ';base64') {
                    return true;
                }

                return false;
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
