<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Exception\InvalidArgumentException;
use Intervention\Image\Image;
use Intervention\Image\Resolution;

/**
 * @mixin \Intervention\Image\Commands\AbstractCommand
 */
trait SetResolutionArguments
{
    /**
     * Parses incoming arguments and returns {@see \Intervention\Image\Resolution} object.
     *
     * @param \Intervention\Image\Image $image
     *
     * @return \Intervention\Image\Resolution
     * @throws \Intervention\Image\Exception\InvalidArgumentException
     */
    protected function getInputResolution(Image $image): Resolution
    {
        $resolution = null;

        switch (count($this->arguments)) {
            case 0:
                throw new InvalidArgumentException("setResolution() expects at least 1 parameters, 0 given");
                break;
            case 1:
                if ($this->argument(0)->value() instanceof Resolution) {
                    // setResolution(new Resolution(...))
                    $resolution = $this->argument(0)->required()->value();
                } else {
                    // setResolution(<XY>)
                    $x          = $this->argument(0)->required()->type('int')->min(0)->value();
                    $y          = $x;
                    $units      = $image->getResolution()->getUnits();
                    $resolution = new Resolution($x, $y, $units);
                }
                break;
            case 2:
                if (is_string($this->argument(1)->value())) {
                    // setResolution(<XY>, 'units')
                    $x          = $this->argument(0)->required()->type('int')->min(0)->value();
                    $y          = $x;
                    $units      = $this->argument(1)->required()->type('string')->value();
                    $resolution = new Resolution($x, $y, $units);
                } else {
                    // setResolution(<X>, <Y>)
                    $x          = $this->argument(0)->required()->type('int')->min(0)->value();
                    $y          = $this->argument(1)->required()->type('int')->min(0)->value();
                    $units      = $image->getResolution()->getUnits();
                    $resolution = new Resolution($x, $y, $units);
                }
                break;
            case 3:
                // setResolution(<X>, <Y>, 'units')
                $x          = $this->argument(0)->required()->type('int')->min(0)->value();
                $y          = $this->argument(1)->required()->type('int')->min(0)->value();
                $units      = $this->argument(2)->required()->type('string')->value();
                $resolution = new Resolution($x, $y, $units);
                break;
            default:
                throw new InvalidArgumentException(sprintf("setResolution() expects at most %s parameters, %s given", 3, count($this->arguments)));
                break;
        }

        if (is_null($resolution)) {
            throw new InvalidArgumentException("setResolution() called with wrong arguments");
        }

        return $resolution;
    }
}
