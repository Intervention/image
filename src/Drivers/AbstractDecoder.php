<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Exception;
use Intervention\Image\Collection;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Traits\CanBuildFilePointer;
use Throwable;

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
            $source = $this->parseFilePath($input);
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
     * Decodes given base 64 encoded data
     *
     * @throws DecoderException
     */
    protected function decodeBase64Data(mixed $input): string
    {
        if (!is_string($input)) {
            throw new DecoderException('Input is not Base64-encoded data.');
        }

        $decoded = base64_decode($input, true);

        if ($decoded === false) {
            throw new DecoderException('Input can not be Base64-decoded.');
        }

        if (base64_encode($decoded) !== str_replace(["\n", "\r"], '', $input)) {
            throw new DecoderException('Input is not Base64-decoded data.');
        }

        return $decoded;
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

    /**
     * Parse and return a given file path or throw detailed exception if the path is invalid
     *
     * @throws DecoderException
     */
    protected function parseFilePath(mixed $path): string
    {
        if (!is_string($path)) {
            throw new DecoderException('Path must be of type string.');
        }

        if ($path === '') {
            throw new DecoderException('Path must not be an empty string.');
        }

        if (strlen($path) > PHP_MAXPATHLEN) {
            throw new DecoderException(
                "Path is longer than the configured max. value of " . PHP_MAXPATHLEN . ".",
            );
        }

        // get info on path
        $dirname = pathinfo($path, PATHINFO_DIRNAME);
        $basename = pathinfo($path, PATHINFO_BASENAME);

        if (!is_dir($dirname)) {
            throw new DecoderException("Directory ('" . $dirname . "') not found.");
        }

        if (!@is_file($path)) {
            throw new DecoderException("File ('" . $basename . "') not found in directory ('" . $dirname . "').");
        }

        return $path;
    }
}
