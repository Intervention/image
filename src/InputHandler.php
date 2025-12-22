<?php

declare(strict_types=1);

namespace Intervention\Image;

use Generator;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\InputHandlerInterface;

class InputHandler implements InputHandlerInterface
{
    /**
     * Create new input handler instance with given decoder classnames
     *
     * @param array<string|DecoderInterface> $decoders
     * @return void
     */
    public function __construct(
        protected array $decoders = [],
        protected ?DriverInterface $driver = null,
    ) {
        //
    }

    /**
     * Static factory method to create input handler for both image and color handling
     *
     * @param array<string|DecoderInterface> $decoders
     */
    public static function withDecoders(array $decoders, ?DriverInterface $driver = null): self
    {
        return new self($decoders, $driver);
    }

    /**
     * {@inheritdoc}
     *
     * @see InputHandlerInterface::handle()
     */
    public function handle(mixed $input): ImageInterface|ColorInterface
    {
        if ($input === null) {
            throw new InvalidArgumentException('Unable to decode null');
        }

        if ($input === '') {
            throw new InvalidArgumentException('Unable to decode empty string');
        }

        // if handler has only one single decoder run it can run directly
        if (count($this->decoders) === 1) {
            return $this->decoders()->current()->decode($input);
        }

        // multiple decoders: try to find the matching decoder for the input
        foreach ($this->decoders() as $decoder) {
            if ($decoder->supports($input)) {
                return $decoder->decode($input);
            }
        }

        throw new NotSupportedException('Unknown input');
    }

    /**
     * Yield all decoders
     */
    private function decoders(): Generator
    {
        foreach ($this->decoders as $decoder) {
            yield $this->decoder($decoder);
        }
    }

    /**
     * Resolve the given classname to an decoder object
     */
    private function decoder(string|DecoderInterface $decoder): DecoderInterface
    {
        if (($decoder instanceof DecoderInterface) && empty($this->driver)) {
            return $decoder;
        }

        if (($decoder instanceof DecoderInterface) && !empty($this->driver)) {
            return $this->driver->specializeDecoder($decoder);
        }

        if (!is_subclass_of($decoder, DecoderInterface::class)) {
            throw new InvalidArgumentException('Decoder must implement ' . DecoderInterface::class);
        }

        $resolved = new $decoder();

        if (!($resolved instanceof DecoderInterface)) {
            throw new DriverException('Failed to resolved decoder ' . $decoder);
        }

        try {
            return empty($this->driver) ? $resolved : $this->driver->specializeDecoder($resolved);
        } catch (NotSupportedException $e) {
            throw new DriverException('Failed to resolved decoder ' . $decoder, previous: $e);
        }
    }
}
