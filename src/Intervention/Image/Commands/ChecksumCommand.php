<?php

namespace Intervention\Image\Commands;

class ChecksumCommand extends AbstractCommand
{
    public function execute($image)
    {
        $colors = array();

        $size = $image->getSize();

        for ($x=0; $x <= ($size->width-1); $x++) {
            for ($y=0; $y <= ($size->height-1); $y++) {
                $colors[] = $image->pickColor($x, $y, 'array');
            }
        }

        $this->setOutput(md5(serialize($colors)));

        return true;
    }
}
