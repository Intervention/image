<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class ResizeModifier implements ModifierInterface
{
    protected $width;
    protected $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        foreach ($image as $frame) {
            $framesize = $frame->getSize();
            $this->modify($frame, 0, 0, 0, 0, $this->width, $this->height, $framesize->getWidth(), $framesize->getHeight());
        }

        return $image;
    }

    /**
     * Wrapper function for 'imagecopyresampled'
     *
     * @param  Image   $image
     * @param  int     $dst_x
     * @param  int     $dst_y
     * @param  int     $src_x
     * @param  int     $src_y
     * @param  int     $dst_w
     * @param  int     $dst_h
     * @param  int     $src_w
     * @param  int     $src_h
     * @return boolean
     */
    protected function modify(FrameInterface $frame, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
    {
        // create new image
        $modified = imagecreatetruecolor($dst_w, $dst_h);

        // get current image
        $gd = $frame->getCore();

        // preserve transparency
        $transIndex = imagecolortransparent($gd);

        if ($transIndex != -1) {
            $rgba = imagecolorsforindex($modified, $transIndex);
            $transColor = imagecolorallocatealpha($modified, $rgba['red'], $rgba['green'], $rgba['blue'], 127);
            imagefill($modified, 0, 0, $transColor);
            imagecolortransparent($modified, $transColor);
        } else {
            imagealphablending($modified, false);
            imagesavealpha($modified, true);
        }

        // copy content from resource
        $result = imagecopyresampled(
            $modified,
            $gd,
            $dst_x,
            $dst_y,
            $src_x,
            $src_y,
            $dst_w,
            $dst_h,
            $src_w,
            $src_h
        );

        imagedestroy($gd);

        // set new content as recource
        $frame->setCore($modified);
    }
}
