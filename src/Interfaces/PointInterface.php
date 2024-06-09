<?php

declare(strict_types=1);

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
     * Set x position
     *
     * @param int $x
     * @return PointInterface
     */
    public function setX(int $x): self;

    /**
     * Set y position
     *
     * @param int $y
     * @return PointInterface
     */
    public function setY(int $y): self;

    /**
     * Set position of point
     *
     * @param int $x
     * @param int $y
     * @return PointInterface
     */
    public function setPosition(int $x, int $y): self;
}
