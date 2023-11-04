<?php

namespace Intervention\Image\Interfaces;

interface InputHandlerInterface
{
    /**
     * Create new instance with an array of decoders
     *
     * @param array $decoders
     */
    public function __construct(array $decoders = []);

    /**
     * Try to decode the given input with each decoder of the the handler chain
     *
     * @param  mixed $input
     * @return ImageInterface|ColorInterface
     */
    public function handle($input): ImageInterface|ColorInterface;
}
