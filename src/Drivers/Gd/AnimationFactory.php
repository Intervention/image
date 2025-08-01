<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\CoreInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AnimationFactory implements AnimationFactoryInterface
{
    public function __construct(
        protected DriverInterface $driver,
        public CoreInterface $core = new Core()
    ) {
        //
    }

    /**
     * @throws AnimationException
     * @throws DecoderException
     * @throws RuntimeException
     */
    public function add(mixed $source, float $delay = 1): self
    {
        $this->core->add(
            $this->driver->handleImageInput($source)->core()->first()->setDelay($delay)
        );

        return $this;
    }

    public function __invoke(): ImageInterface
    {
        return new Image(
            $this->driver,
            $this->core
        );
    }
}
