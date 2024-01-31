<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\InputHandlerInterface;

abstract class AbstractInputHandler implements InputHandlerInterface
{
    protected array $decoders = [];

    public function __construct(array $decoders = [])
    {
        $this->decoders = count($decoders) ? $decoders : $this->decoders;
    }

    /**
     * {@inheritdoc}
     *
     * @see InputHandlerInterface::handle()
     */
    public function handle($input): ImageInterface|ColorInterface
    {
        return $this->chain()->handle($input);
    }

    /**
     * Stack the decoder array into a nested decoder object
     *
     * @return AbstractDecoder
     */
    protected function chain(): AbstractDecoder
    {
        if (count($this->decoders) == 0) {
            throw new DecoderException('No decoders found in ' . $this::class);
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
}
