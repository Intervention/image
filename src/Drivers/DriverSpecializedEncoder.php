<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncoderInterface;

abstract class DriverSpecializedEncoder implements EncoderInterface
{
    public function __construct(
        protected EncoderInterface $encoder,
        protected DriverInterface $driver
    ) {
    }

    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * Magic method to read attributes of underlying endcoder
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->encoder->$name;
    }

    /**
     * Get return value of callback through output buffer
     *
     * @param  callable $callback
     * @return string
     */
    protected function getBuffered(callable $callback): string
    {
        ob_start();
        $callback();
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
}
