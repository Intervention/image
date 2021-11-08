<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;

/*

# contain
1. Scale (keep aspect ratio) Original to fit Target
2. Scale (keep aspect ratio) Up/Down to fit Target (obsolete)

# cover
1. Scale (keep aspect ratio) Target to fit Original
2. Scale (keep aspect ratio) Up/Down to fit Target

 */

class CropResizeModifier implements ModifierInterface
{
    protected $width;
    protected $height;
    protected $position;

    public function __construct(int $width, int $height, string $position)
    {
        $this->width = $width;
        $this->height = $height;
        $this->position = $position;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        echo "<pre>";
        var_dump($this->getCropSize($image));
        var_dump($this->getResizeSize($image));
        echo "</pre>";
        exit;

        // foreach ($image as $frame) {
        //     $this->modify($frame);
        // }

        return $image;
    }

    protected function getCropSize(ImageInterface $image): SizeInterface
    {
        $resizer = new Resizer(new Size($this->width, $this->height));
        $resizer->width($image->width());
        $resizer->height($image->height());

        return $resizer->scale()->align($this->position);
    }

    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        $resizer = new Resizer($this->getCropSize($image));
        $resizer->width($this->width);
        $resizer->height($this->height);

        return $resizer->scale()->align($this->position);
    }

    /**
     * Wrapper function for 'imagecopyresampled'
     *
     * @param  FrameInterface $frame
     * @param  int            $dst_x
     * @param  int            $dst_y
     * @param  int            $src_x
     * @param  int            $src_y
     * @param  int            $dst_w
     * @param  int            $dst_h
     * @param  int            $src_w
     * @param  int            $src_h
     * @return void
     */
    protected function modify(FrameInterface $frame): void
    {
        // create new image
        $modified = imagecreatetruecolor(
            $this->resizeTo->getWidth(),
            $this->resizeTo->getHeight()
        );

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
            $this->resizeTo->getPivot()->getX(),
            $this->resizeTo->getPivot()->getY(),
            $this->cropTo->getPivot()->getX(),
            $this->cropTo->getPivot()->getY(),
            $this->resizeTo->getWidth(),
            $this->resizeTo->getHeight(),
            $this->cropTo->getWidth(),
            $this->cropTo->getHeight()
        );

        imagedestroy($gd);

        // set new content as recource
        $frame->setCore($modified);
    }
}
