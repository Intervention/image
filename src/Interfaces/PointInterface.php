<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface PointInterface
{
    /**
     * Return x position
     */
    public function x(): int;

    /**
     * Return y position
     */
    public function y(): int;

    /**
     * Set x position
     */
    public function setX(int $x): self;

    /**
     * Set y position
     */
    public function setY(int $y): self;

    /**
     * Move X coordinate
     */
    public function moveX(int $value): self;

    /**
     * Move Y coordinate
     */
    public function moveY(int $value): self;

    /**
     * Move position of current point by given coordinates
     */
    public function move(int $x, int $y): self;

    /**
     * Set position of point
     */
    public function setPosition(int $x, int $y): self;

    /**
     * Rotate point counter clock wise around given pivot point
     */
    public function rotate(float $angle, self $pivot): self;
}
