<?php

namespace Intervention\Image\Drivers\Abstract\Decoders;

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
                $this->fail();
            }

            return $this->successor->handle($input);
        }

        return $decoded;
    }

    protected function hasSuccessor(): bool
    {
        return $this->successor !== null;
    }

    protected function fail(): void
    {
        throw new DecoderException("Unable to decode given input.");
    }

    protected function inputType($input): AbstractType
    {
        return MimeSniffer::createFromString($input)->getType();
    }
}
