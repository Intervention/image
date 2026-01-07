<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Gif\DisposalMethod;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AnimationFactory implements AnimationFactoryInterface
{
    /**
     * Image source of animation frames.
     *
     * @var array<mixed>
     */
    protected array $sources = [];

    /**
     * Frame delays of animation in seconds.
     *
     * @var array<float>
     */
    protected array $delays = [];

    /**
     * Create new instance.
     */
    public function __construct(
        protected DriverInterface $driver,
        protected int $width,
        protected int $height,
        null|callable $animation = null,
    ) {
        if (is_callable($animation)) {
            $animation($this);
        }
    }

    /**
     * Create the animation statically by calling given callable.
     */
    public static function build(DriverInterface $driver, int $width, int $height, callable $animation): ImageInterface
    {
        return (new self($driver, $width, $height, $animation))->animation();
    }

    /**
     * {@inheritdoc}
     *
     * @see AnimationFactoryInterface::add()
     */
    public function add(mixed $source, float $delay = 1): AnimationFactoryInterface
    {
        $this->sources[] = $source;
        $this->delays[] = $delay;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see AnimationFactoryInterface::add()
     */
    public function animation(): ImageInterface
    {
        $frames = array_map($this->buildFrame(...), $this->sources, $this->delays);

        return new Image(
            $this->driver,
            $this->driver->createCore($frames),
        );
    }

    /**
     * Build frame from given image source and delay.
     */
    private function buildFrame(mixed $source, float $delay): FrameInterface
    {
        // decode image source
        $image = $this->driver->handleImageInput($source);

        // adjust size if necessary
        if ($image->width() !== $this->width || $image->height() !== $this->height) {
            $image->cover($this->width, $this->height); // todo: make resizing method selectable by api
        }

        // make sure to have to given output size only if resizing method is selectable by api
        // $image->resizeCanvas($this->width, $this->height);

        // return ready-made frame with all attributes
        return $image
            ->core()
            ->first()
            ->setDelay($delay)
            ->setDisposalMethod(DisposalMethod::BACKGROUND->value);
    }
}
