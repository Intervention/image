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
     * Image sources of animation frames.
     *
     * @var array<mixed>
     */
    protected array $sources = [];

    /**
     * Frame delays of animation frames in seconds.
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
     * Frame processing arguments of calls.
     *
     * @var array<null|array<mixed>>
     */
    protected array $processingArguments = [];

    /**
     * Create new instance.
     */
    public function __construct(
        protected int $width,
        protected int $height,
        null|callable $animation = null,
    ) {
        if (is_callable($animation)) {
            $animation($this);
        }
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
    public function build(DriverInterface $driver): ImageInterface
    {
        if (count($this->sources) === 0) {
            return $driver->createImage($this->width, $this->height);
        }

        $frames = array_map(
            $this->buildFrame(...),
            array_fill(0, count($this->sources), $driver),
            $this->sources,
            $this->delays,
            $this->processingCalls,
            $this->processingArguments,
        );

        return new Image($driver, $driver->createCore($frames));
    }

    /**
     * Build frame from given image source and delay.
     *
     * @param null|array<mixed> $processingArguments
     */
    private function buildFrame(
        DriverInterface $driver,
        mixed $source,
        float $delay,
        ?string $processingCall = null,
        ?array $processingArguments = null,
    ): FrameInterface {
        // decode image source
        $source = $driver->handleImageInput($source);

        // adjust size if necessary
        if ($source->width() !== $this->width || $source->height() !== $this->height) {
            $source->cover($this->width, $this->height);
        }

        // apply processing call if available
        if ($processingCall) {
            call_user_func_array([$source, $processingCall], $processingArguments);
        }

        // make sure to have to given output size only if resizing method is selectable by api
        // $image->resizeCanvas($this->width, $this->height);

        // return ready-made frame with all attributes
        return $source
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
