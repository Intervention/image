<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Encoders\MediaTypeEncoder;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

final class HeicTest extends BaseTestCase
{
    public function testEncoding(): void
    {
        $manager = ImageManager::imagick();
        $img = $manager->read($manager->create(100, 100)->encode(new MediaTypeEncoder('image/heic'))->toFilePointer());
        $this->assertInstanceOf(Image::class, $img);
    }
}
