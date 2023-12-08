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

    /**
     * Set position of point
     *
     * @param  int $x
     * @param  int $y
     * @return PointInterface
     */
    public function setPosition(int $x, int $y): PointInterface;
}
