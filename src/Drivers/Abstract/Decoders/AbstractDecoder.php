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
        if (! function_exists('exif_read_data')) {
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
}
