<?php

namespace Intervention\Image\Interfaces;

interface PointInterface
{
    /**
     * Return x position
     *
     * @return int
     */
    public function x(): int;

    /**
     * Return y position
     *
     * @return int
     */
    public function y(): int;
}
