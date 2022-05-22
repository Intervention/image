<?php

namespace Intervention\Image\Drivers\Abstract;

abstract class AbstractFrame
{
    /**
     * Set the frame core
     *
     * Input is losely typed and depending on the driver.
     * Might be GdImage or Imagick but should be open to
     * add more drivers.
     *
     * @param mixed $core
     * @return AbstractFrame
     */
    public function setCore($core): self
    {
        $this->core = $core;

        return $this;
    }
}
