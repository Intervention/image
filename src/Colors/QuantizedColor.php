<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

class QuantizedColor implements ColorInterface
{
    /**
     * Create instance.
     */
    public function __construct(public ColorInterface $color, public int $population = 0)
    {
        //
    }

    /**
     * Build unique identifier of color.
     */
    public function hash(): string
    {
        $channelValues = array_map(
            fn(ColorChannelInterface $channel): int|float => $channel->value(),
            $this->color->channels(),
        );

        return md5(implode(',', $channelValues));
    }

    /**
     * Increase population of color.
     */
    public function increasePopulation(): self
    {
        $this->population++;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::colorspace()
     */
    public function colorspace(): ColorspaceInterface
    {
        return $this->color->colorspace();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toString()
     */
    public function toString(): string
    {
        return $this->color->toString();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toHex()
     */
    public function toHex(bool $prefix = false): string
    {
        return $this->color->toHex($prefix);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::channels()
     */
    public function channels(): array
    {
        return $this->color->channels();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::channel()
     */
    public function channel(string $classname): ColorChannelInterface
    {
        return $this->color->channel($classname);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::alpha()
     */
    public function alpha(): ColorChannelInterface
    {
        return $this->color->alpha();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toColorspace()
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): ColorInterface
    {
        return $this->color->toColorspace($colorspace);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isGrayscale()
     */
    public function isGrayscale(): bool
    {
        return $this->color->isGrayscale();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isTransparent()
     */
    public function isTransparent(): bool
    {
        return $this->color->isTransparent();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isClear()
     */
    public function isClear(): bool
    {
        return $this->color->isClear();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::withTransparency()
     */
    public function withTransparency(float $transparency): ColorInterface
    {
        return $this->color->withTransparency($transparency);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::withBrightness()
     */
    public function withBrightness(int $level): ColorInterface
    {
        return $this->color->withBrightness($level);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::withSaturation()
     */
    public function withSaturation(int $level): ColorInterface
    {
        return $this->color->withSaturation($level);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::withInversion()
     */
    public function withInversion(): ColorInterface
    {
        return $this->color->withInversion();
    }
}
