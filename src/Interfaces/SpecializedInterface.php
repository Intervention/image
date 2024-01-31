<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface SpecializedInterface
{
    public function __construct();

    /**
     * Return current driver instance
     *
     * @return DriverInterface
     */
    public function driver(): DriverInterface;

    /**
     * Return driverless generic object
     *
     * @return object
     */
    public function generic(): object;

    /**
     * Build driver specialized version of given generic object for given driver
     *
     * @param object $generic
     * @param DriverInterface $driver
     * @return SpecializedInterface
     */
    public static function buildSpecialized(object $generic, DriverInterface $driver): self;
}
