<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd;

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
     * Create the end product of the factory statically by calling given callable
     */
    public static function build(DriverInterface $driver, callable $animation): ImageInterface
    {
        $factory = new self($driver);

        $animation($factory);

        return $factory->animation();
    }

    /**
     * {@inheritdoc}
     *
     * @see AnimationFactoryInterface::animation()
     */
    public function animation(): ImageInterface
    {
        return new Image(
            $this->driver,
            $this->core
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see AnimationFactoryInterface::add()
     */
    public function add(mixed $source, float $delay = 1): self
    {
        $this->core->add(
            $this->driver->handleImageInput($source)->core()->first()->setDelay($delay)
        );

        return $this;
    }
}
