<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class AbstractInputHandler
{
    /**
     * Array of decoders which will be stacked into to the input handler chain
     */
    protected $decoders = [];

    /**
     * Stack the decoder array into a nested decoder object
     *
     * @return AbstractDecoder
     */
    protected function chain(): AbstractDecoder
    {
        if (count($this->decoders) == 0) {
            throw new DecoderException('No decoders found in ' . get_class($this));
        }

        // get instance of last decoder in stack
        list($classname) = array_slice(array_reverse($this->decoders), 0, 1);
        $chain = new $classname();

        // build decoder chain
        foreach (array_slice(array_reverse($this->decoders), 1) as $classname) {
            $chain = new $classname($chain);
        }

        return $chain;
    }

    /**
     * Try to decode the given input with each decoder of the the handler chain
     *
     * @param  mixed $var
     * @return ImageInterface|ColorInterface
     */
    public function handle($input): ImageInterface|ColorInterface
    {
        return $this->chain()->handle($input);
    }
}
