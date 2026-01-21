<?php

declare(strict_types=1);

namespace Intervention\Image;

use Error;
use Intervention\Gif\DisposalMethod;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;

class AnimationFactory implements AnimationFactoryInterface
{
    /**
     * Current frame number.
     */
    protected int $currentFrameNumber = 0;

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
     * Frame processing call names.
     *
     * @var array<null|string>
     */
    protected array $processingCalls = [];

    /**
     * Frame processing call arguments.
     *
     * @var array<null|array<mixed>>
     */
    protected array $processingArguments = [];

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
    public function add(mixed $image, float $delay = 1): AnimationFactoryInterface
    {
        $this->currentFrameNumber++;

        $this->sources[$this->currentFrameNumber] = $image;
        $this->delays[$this->currentFrameNumber] = $delay;
        $this->processingCalls[$this->currentFrameNumber] = null;
        $this->processingArguments[$this->currentFrameNumber] = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see AnimationFactoryInterface::animation()
     */
    public function animation(): ImageInterface
    {
        $frames = array_map(
            $this->buildFrame(...),
            $this->sources,
            $this->delays,
            $this->processingCalls,
            $this->processingArguments,
        );

        return new Image(
            $this->driver,
            $this->driver->createCore($frames),
        );
    }

    /**
     * Build frame from given image source and delay.
     *
     * @param null|array<mixed> $processingArguments
     */
    private function buildFrame(
        mixed $image,
        float $delay,
        ?string $processingCall = null,
        ?array $processingArguments = null,
    ): FrameInterface {
        // decode image source
        $image = $this->driver->handleImageInput($image);

        // adjust size if necessary
        if ($image->width() !== $this->width || $image->height() !== $this->height) {
            $image->cover($this->width, $this->height);
        }

        // apply processing call if available
        if ($processingCall) {
            call_user_func_array([$image, $processingCall], $processingArguments);
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

    /**
     * Collect processing calls on frame images.
     *
     * @param array<null|array<mixed>> $arguments
     */
    public function __call(string $name, array $arguments): self
    {
        if (!method_exists(Image::class, $name)) {
            throw new Error('Call to undefined method ' . Image::class . '::' . $name . '()');
        }

        $this->processingCalls[$this->currentFrameNumber] = $name;
        $this->processingArguments[$this->currentFrameNumber] = $arguments;

        return $this;
    }
}
