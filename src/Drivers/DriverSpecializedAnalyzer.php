<?php

namespace Intervention\Image\Drivers;

use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\DriverInterface;

abstract class DriverSpecializedAnalyzer implements AnalyzerInterface
{
    public function __construct(
        protected AnalyzerInterface $analyzer,
        protected DriverInterface $driver
    ) {
    }

    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * Magic method to read attributes of underlying analyzer
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->analyzer->$name;
    }
}
