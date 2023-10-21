<?php

namespace Intervention\Image\Interfaces;

interface PointInterface
{
    /**
     * Return x position
     *
     * @return int
     */
    public function getX(): int;

    /**
     * Return y position
     *
     * @return int
     */
    public function getY(): int;
}
