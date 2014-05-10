<?php

namespace Intervention\Image\Imagick\Commands;

class ColorizeCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $red = $this->getArgument(0);
        $green = $this->getArgument(1);
        $blue = $this->getArgument(2);

        // normalize colorize levels
        $red = $this->normalizeLevel($red);
        $green = $this->normalizeLevel($green);
        $blue = $this->normalizeLevel($blue);

        $qrange = $image->getCore()->getQuantumRange();

        // apply
        $image->getCore()->levelImage(0, $red, $qrange['quantumRangeLong'], \Imagick::CHANNEL_RED);
        $image->getCore()->levelImage(0, $green, $qrange['quantumRangeLong'], \Imagick::CHANNEL_GREEN);
        $image->getCore()->levelImage(0, $blue, $qrange['quantumRangeLong'], \Imagick::CHANNEL_BLUE);

        return true;
    }

    private function normalizeLevel($level)
    {
        if ($level > 0) {
            return $level/5;
        } else {
            return ($level+100)/100;
        }
    }
}
