<?php

namespace Intervention\Image\Colors\Traits;

use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ColorChannelInterface;

trait CanHandleChannels
{
    public function channels(): array
    {
        return $this->channels;
    }

    public function channel(string $classname): ColorChannelInterface
    {
        $channels = array_filter($this->channels(), function (ColorChannelInterface $channel) use ($classname) {
            return get_class($channel) == $classname;
        });

        if (count($channels) == 0) {
            throw new ColorException('Channel ' . $classname . ' could not be found.');
        }

        return reset($channels);
    }

    public function normalize(): array
    {
        return array_map(function (ColorChannelInterface $channel) {
            return $channel->normalize();
        }, $this->channels());
    }
}
