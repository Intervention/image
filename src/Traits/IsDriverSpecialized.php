<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Interfaces\DriverInterface;

trait IsDriverSpecialized
{
    protected DriverInterface $driver;

    /**
     * {@inheritdoc}
     *
     * @see SpecializedInterface::driver()
     */
    public function driver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     *
     * @see SpecializedInterface::driver()
     */
    public function setDriver(DriverInterface $driver): self
    {
        $this->driver = $driver;

        return $this;
    }
}
