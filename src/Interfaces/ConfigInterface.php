<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\InputException;

interface ConfigInterface
{
    /**
     * Return value of given config option
     *
     * @param string $name
     * @return mixed
     */
    public function option(string $name, mixed $default = null): mixed;

    /**
     * Set value of given config option
     *
     * @param string $name
     * @param mixed $value
     * @throws InputException
     * @return ConfigInterface
     */
    public function setOption(string $name, mixed $value): self;

    /**
     * Set values of given config options
     *
     * @param mixed $options
     * @throws InputException
     * @return ConfigInterface
     */
    public function setOptions(mixed ...$options): self;
}