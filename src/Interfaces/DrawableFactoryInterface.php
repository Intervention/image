<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DrawableFactoryInterface
{
    /**
     * Return drawable object
     *
     * @return DrawableInterface
     */
    public function create(): DrawableInterface;

    /**
     * Return drawable object
     *
     * @return DrawableInterface
     */
    public function __invoke(): DrawableInterface;
}
