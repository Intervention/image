<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Colors;

use Intervention\Image\Colors\Profile;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Profile::class)]
final class ProfileTest extends BaseTestCase
{
    public function testFromPath(): void
    {
        $profile = Profile::fromPath(Resource::create()->path());
        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertTrue($profile->size() > 0);
    }

    public function testFromPathNotFound(): void
    {
        $this->expectException(FileNotFoundException::class);
        Profile::fromPath('/tmp/nonexistent_profile_' . hrtime(true) . '.icc');
    }
}
