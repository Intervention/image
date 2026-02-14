<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use ReflectionClass;
use Stringable;

abstract class AbstractColor implements ColorInterface, Stringable
{
    /**
     * Color channels.
     *
     * @var array<ColorChannelInterface>
     */
    protected array $channels;

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::channels()
     */
    public function channels(): array
    {
        return $this->channels;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::channel()
     *
     * @throws NotSupportedException
     */
    public function channel(string $classname): ColorChannelInterface
    {
        $channels = array_filter(
            $this->channels(),
            fn(ColorChannelInterface $channel): bool => $channel::class === $classname,
        );

        if (count($channels) === 0) {
            throw new NotSupportedException('Color channel ' . $classname . ' could not be found');
        }

        return reset($channels);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toColorspace()
     *
     * @throws InvalidArgumentException
     * @throws NotSupportedException
     */
    public function toColorspace(string|ColorspaceInterface $colorspace): ColorInterface
    {
        if (is_string($colorspace) && !class_exists($colorspace)) {
            throw new InvalidArgumentException('Unknown color space (' . $colorspace . ') as conversion target');
        }

        $colorspace = is_string($colorspace) ? new $colorspace() : $colorspace;

        if (!$colorspace instanceof ColorspaceInterface) {
            throw new InvalidArgumentException('Given color space must implement ' . ColorspaceInterface::class);
        }

        return $colorspace->importColor($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isTransparent()
     */
    public function isTransparent(): bool
    {
        return $this->alpha()->value() < $this->alpha()->max();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::isClear()
     */
    public function isClear(): bool
    {
        return $this->alpha()->value() == 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::withTransparency()
     */
    public function withTransparency(float $transparency): ColorInterface
    {
        $color = clone $this;

        $color->channels = array_map(
            fn(ColorChannelInterface $channel): ColorChannelInterface =>
            $channel instanceof AlphaChannel ? $channel::fromNormalized($transparency) : $channel,
            $this->channels
        );

        return $color;
    }

    /**
     * Show debug info for the current color.
     *
     * @return array<string, int>
     */
    public function __debugInfo(): array
    {
        return array_reduce($this->channels(), function (array $result, ColorChannelInterface $item) {
            $key = strtolower((new ReflectionClass($item))->getShortName());
            $result[$key] = $item->toString();
            return $result;
        }, []);
    }

    /**
     * Clone color.
     */
    public function __clone(): void
    {
        foreach ($this->channels as $key => $channel) {
            $this->channels[$key] = clone $channel;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::__toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
