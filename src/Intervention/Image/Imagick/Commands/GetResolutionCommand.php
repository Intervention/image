<?php

namespace Intervention\Image\Imagick\Commands;

use Imagick;
use Intervention\Image\Commands\AbstractCommand;
use Intervention\Image\Resolution;

class GetResolutionCommand extends AbstractCommand
{
    protected static $unitsMap = [
        Imagick::RESOLUTION_UNDEFINED           => Resolution::UNITS_UNKNOWN,
        Imagick::RESOLUTION_PIXELSPERINCH       => Resolution::UNITS_PPI,
        Imagick::RESOLUTION_PIXELSPERCENTIMETER => Resolution::UNITS_PPCM,
    ];

    /**
     * Reads resolution of given image instance.
     *
     * @param \Intervention\Image\Image $image
     *
     * @return boolean
     */
    public function execute($image)
    {
        /** @var \Imagick $core */
        $core       = $image->getCore();
        $units      = $core->getImageUnits();
        $resolution = $core->getImageResolution();
        $resolution = new Resolution($resolution['x'], $resolution['y'], $this->getResolutionUnits($units));

        $this->setOutput($resolution);

        return true;
    }

    protected function getResolutionUnits(int $units): string
    {
        return static::$unitsMap[$units] ?? Resolution::UNITS_UNKNOWN;
    }

    protected function getImagickUnits(string $units): int
    {
        return array_search($units, static::$unitsMap, true) ?: Imagick::RESOLUTION_UNDEFINED;
    }
}
