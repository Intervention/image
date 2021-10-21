<?php

namespace Intervention\Image\Drivers\Abstract\Encoders;

use Intervention\Image\Interfaces\EncoderInterface;

abstract class AbstractEncoder
{
    public function __construct(protected ?int $quality = null)
    {
        //
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

    public function setQuality(int $quality): EncoderInterface
    {
        $this->quality = $quality;

        return $this;
    }

    public function getQuality(): int
    {
        return $this->quality;
    }
}
