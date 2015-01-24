<?php

namespace Intervention\Image\Imagick\Commands;

class ColorizeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Changes balance of different RGB color channels
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $red = $this->argument(0)->between(-100, 100)->required()->value();
        $green = $this->argument(1)->between(-100, 100)->required()->value();
        $blue = $this->argument(2)->between(-100, 100)->required()->value();

        // normalize colorize levels
        $red = $this->normalizeLevel($red);
        $green = $this->normalizeLevel($green);
        $blue = $this->normalizeLevel($blue);

        // apply on each frame
        foreach ($image as $frame) {
            $qrange = $frame->getCore()->getQuantumRange();
            $frame->getCore()->levelImage(0, $red, $qrange['quantumRangeLong'], \Imagick::CHANNEL_RED);
            $frame->getCore()->levelImage(0, $green, $qrange['quantumRangeLong'], \Imagick::CHANNEL_GREEN);
            $frame->getCore()->levelImage(0, $blue, $qrange['quantumRangeLong'], \Imagick::CHANNEL_BLUE);
        }

        return true;
    }

    /**
     * Return normalized level value
     *
     * @param  numeric $level
     * @return numeric
     */
    private function normalizeLevel($level)
    {
        if ($level > 0) {
            return $level/5;
        } else {
            return ($level+100)/100;
        }
    }
}
