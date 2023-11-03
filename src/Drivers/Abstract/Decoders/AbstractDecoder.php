<?php

namespace Intervention\Image\Drivers\Abstract\Decoders;

use Exception;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\AbstractType;

abstract class AbstractDecoder implements DecoderInterface
{
    public function __construct(protected ?AbstractDecoder $successor = null)
    {
        //
    }

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

    protected function hasSuccessor(): bool
    {
        return $this->successor !== null;
    }

    protected function inputType($input): AbstractType
    {
        return MimeSniffer::createFromString($input)->getType();
    }

    protected function decodeExifData(string $image_data): array
    {
        if (!function_exists('exif_read_data')) {
            return [];
        }

        try {
            $pointer = fopen('php://temp', 'rw');
            fputs($pointer, $image_data);
            rewind($pointer);
            $data = @exif_read_data($pointer, null, true);
            fclose($pointer);
        } catch (Exception $e) {
            $data = [];
        }

        return is_array($data) ? $data : [];
    }

    protected function isValidBase64($input): bool
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
