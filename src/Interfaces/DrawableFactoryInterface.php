<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DrawableFactoryInterface
{
    /**
     * Create factory statically
     *
     * @param null|callable|DrawableInterface $init
     * @return DrawableFactoryInterface
     */
    public static function create(null|callable|DrawableInterface $init = null): self;

    /**
     * Return finished object
     *
     * @return DrawableInterface
     */
    public function __invoke(): DrawableInterface;
}
