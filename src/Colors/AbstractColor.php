<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;

abstract class AbstractColor implements ColorInterface
{
    /**
     * Color channels
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
     */
    public function channel(string $classname): ColorChannelInterface
    {
        $channels = array_filter($this->channels(), function (ColorChannelInterface $channel) use ($classname) {
            return $channel::class == $classname;
        });

        if (count($channels) == 0) {
            throw new ColorException('Color channel ' . $classname . ' could not be found.');
        }

        return reset($channels);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::normalize()
     */
    public function normalize(): array
    {
        return array_map(function (ColorChannelInterface $channel) {
            return $channel->normalize();
        }, $this->channels());
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::toArray()
     */
    public function toArray(): array
    {
        return array_map(function (ColorChannelInterface $channel) {
            return $channel->value();
        }, $this->channels());
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorInterface::convertTo()
     */
    public function convertTo(string|ColorspaceInterface $colorspace): ColorInterface
    {
        $colorspace = match (true) {
            is_object($colorspace) => $colorspace,
            default => new $colorspace(),
        };

        return $colorspace->importColor($this);
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
