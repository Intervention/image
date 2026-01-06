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
     * @see ColorInterface::normalizedChannelValues()
     */
    public function normalizedChannelValues(): array
    {
        return array_map(
            fn(ColorChannelInterface $channel): float => $channel->normalizedValue(),
            $this->channels(),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toArray()
     */
    public function toArray(): array
    {
        return array_map(
            fn(ColorChannelInterface $channel): int|float => $channel->value(),
            $this->channels()
        );
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
     * Show debug info for the current color.
     *
     * @return array<string, int>
     */
    public function __debugInfo(): array
    {
        return array_reduce($this->channels(), function (array $result, ColorChannelInterface $item) {
            $key = strtolower((new ReflectionClass($item))->getShortName());
            $result[$key] = $item->value();
            return $result;
        }, []);
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
