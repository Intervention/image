<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

trait CanBufferOutput
{
    /**
     * Get return value of callback through output buffer
     *
     * @param callable $callback
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
