<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\InputHandlerInterface;

abstract class AbstractInputHandler implements InputHandlerInterface
{
    /**
     * Decoder classnames in hierarchical order
     *
     * @var array<string|DecoderInterface>
     */
    protected array $decoders = [];

    /**
     * Create new input handler instance with given decoder classnames
     *
     * @param array<string|DecoderInterface> $decoders
     * @return void
     */
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
     * @throws DecoderException
     * @return AbstractDecoder
     */
    protected function chain(): AbstractDecoder
    {
        if (count($this->decoders) == 0) {
            throw new DecoderException('No decoders found in ' . $this::class);
        }

        // get last decoder in stack
        list($decoder) = array_slice(array_reverse($this->decoders), 0, 1);
        $chain = ($decoder instanceof DecoderInterface) ? $decoder : new $decoder();

        // only accept DecoderInterface
        if (!($chain instanceof DecoderInterface)) {
            throw new DecoderException('Decoder must implement in ' . DecoderInterface::class);
        }

        // build decoder chain
        foreach (array_slice(array_reverse($this->decoders), 1) as $decoder) {
            $chain = ($decoder instanceof DecoderInterface) ? new ($decoder::class)($chain) : new $decoder($chain);
        }

        return $chain;
    }
}
