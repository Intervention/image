<?php

namespace Intervention\Image\Drivers;

use Exception;
use Intervention\Image\Collection;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\CollectionInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanBuildFilePointer;

abstract class AbstractDecoder implements DecoderInterface
{
    use CanBuildFilePointer;

    public function __construct(protected ?AbstractDecoder $successor = null)
    {
        //
    }

    /**
     * Try to decode given input to image or color object
     *
     * @param mixed $input
     * @return ImageInterface|ColorInterface
     * @throws DecoderException
     */
    final public function handle($input): ImageInterface|ColorInterface
    {
        try {
            $decoded = $this->decode($input);
        } catch (DecoderException $e) {
            if (!$this->hasSuccessor()) {
                throw new DecoderException($e->getMessage());
            }

            return $this->successor->handle($input);
        }

        return $decoded;
    }

    /**
     * Determine if current decoder has a successor
     *
     * @return bool
     */
    protected function hasSuccessor(): bool
    {
        return $this->successor !== null;
    }

    /**
     * Return media type (MIME) of given input
     *
     * @param string $input
     * @return string
     */
    protected function mediaType(string $input): string
    {
        $pointer = $this->buildFilePointer($input);
        $type = mime_content_type($pointer);
        fclose($pointer);

        return $type;
    }

    /**
     * Extract and return EXIF data from given image data string
     *
     * @param string $image_data
     * @return CollectionInterface
     */
    protected function extractExifData(string $image_data): CollectionInterface
    {
        if (!function_exists('exif_read_data')) {
            return new Collection();
        }

        try {
            $pointer = $this->buildFilePointer($image_data);
            $data = @exif_read_data($pointer, null, true);
            fclose($pointer);
        } catch (Exception $e) {
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
     * @param mixed $value
     * @return object
     */
    protected function parseDataUri($value): object
    {
        $pattern = "/^data:(?P<mediatype>\w+\/[-+.\w]+)?" .
            "(?P<parameters>(;[-\w]+=[-\w]+)*)(?P<base64>;base64)?,(?P<data>.*)/";

        $result = preg_match($pattern, $value, $matches);

        return new class ($matches, $result)
        {
            private $matches;
            private $result;

            public function __construct($matches, $result)
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

            public function parameters(): array
            {
                if (isset($this->matches['parameters']) && !empty($this->matches['parameters'])) {
                    return explode(';', trim($this->matches['parameters'], ';'));
                }

                return [];
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
