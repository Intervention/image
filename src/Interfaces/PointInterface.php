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
     * Move X coordinate
     *
     * @param int $value
     */
    public function moveX(int $value): self;

    /**
     * Move Y coordinate
     *
     * @param int $value
     */
    public function moveY(int $value): self;

    /**
     * Move position of current point by given coordinates
     *
     * @param int $x
     * @param int $y
     * @return PointInterface
     */
    public function move(int $x, int $y): self;

    /**
     * Set position of point
     *
     * @param int $x
     * @param int $y
     * @return PointInterface
     */
    public function setPosition(int $x, int $y): self;

    /**
     * Rotate point counter clock wise around given pivot point
     *
     * @param float $angle
     * @param PointInterface $pivot
     * @return PointInterface
     */
    public function rotate(float $angle, self $pivot): self;
}
