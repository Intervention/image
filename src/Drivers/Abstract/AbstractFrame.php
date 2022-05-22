<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Interfaces\FrameInterface;

abstract class AbstractFrame implements FrameInterface
{
    /**
     * Set the frame core
     *
     * Input is losely typed and depending on the driver.
     * Might be GdImage or Imagick but should be open to
     * add more drivers.
     *
     * @param mixed $core
     * @return FrameInterface
     */
    public function setCore($core): FrameInterface
    {
        $this->core = $core;

        return $this;
    }
}
