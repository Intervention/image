<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Colors\Profile;
use Intervention\Image\Tests\BaseTestCase;

class ProfileTest extends BaseTestCase
{
    public function testFromPath(): void
    {
        $profile = Profile::fromPath($this->getTestResourcePath());
        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertTrue($profile->size() > 0);
    }
}
