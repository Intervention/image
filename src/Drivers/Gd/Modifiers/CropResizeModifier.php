<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Geometry\Resizer;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Traits\CanResizeGeometrically;

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
    use CanResizeGeometrically;

    protected $target;
    protected $position;

    public function __construct(SizeInterface $target, string $position = 'top-left')
    {
        $this->target = $target;
        $this->position = $position;
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->getCropSize($image);
        $resize = $this->getResizeSize($image);

        foreach ($image as $frame) {
            $this->modify($frame, $crop, $resize);
        }

        return $image;
    }

    protected function getCropSize(ImageInterface $image): SizeInterface
    {
        $size = $this->resizeGeometrically($this->target)
                ->toWidth($image->width())
                ->toHeight($image->height())
                ->scale();

        return $size->alignPivotTo(
            $image->getSize()->alignPivot($this->position),
            $this->position
        );
    }

    protected function getResizeSize(ImageInterface $image): SizeInterface
    {
        return $this->resizeGeometrically($this->getCropSize($image))
                ->toWidth($this->target->getWidth())
                ->toHeight($this->target->getHeight())
                ->scale();
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
    protected function modify(FrameInterface $frame, SizeInterface $crop, SizeInterface $resize): void
    {
        // create new image
        $modified = imagecreatetruecolor(
            $resize->getWidth(),
            $resize->getHeight()
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
            $resize->getPivot()->getX(),
            $resize->getPivot()->getY(),
            $crop->getPivot()->getX(),
            $crop->getPivot()->getY(),
            $resize->getWidth(),
            $resize->getHeight(),
            $crop->getWidth(),
            $crop->getHeight()
        );

        imagedestroy($gd);

        // set new content as recource
        $frame->setCore($modified);
    }
}
