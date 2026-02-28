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
    public function testConstructor(): void
    {
        $profile = new Profile('data');
        $this->assertInstanceOf(Profile::class, $profile);
    }

    public function testConstructorFromResource(): void
    {
        $profile = new Profile(fopen('php://temp', 'r'));
        $this->assertInstanceOf(Profile::class, $profile);
    }

    public function testFromPath(): void
    {
        $profile = Profile::fromPath(Resource::create()->path());
        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertTrue($profile->size() > 0);
    }

    public function testFromPathReturnsNonEmptyData(): void
    {
        $profile = Profile::fromPath(Resource::create()->path());
        $this->assertNotEmpty($profile->toString());
    }

    public function testFromPathNotFound(): void
    {
        $this->expectException(FileNotFoundException::class);
        Profile::fromPath('/tmp/nonexistent_profile_' . hrtime(true) . '.icc');
    }

    public function testToString(): void
    {
        $profile = new Profile('foo');
        $this->assertEquals('foo', $profile->toString());
    }

    public function testCastToString(): void
    {
        $profile = new Profile('foo');
        $this->assertEquals('foo', (string) $profile);
    }

    public function testToFilePointer(): void
    {
        $profile = new Profile('foo');
        $fp = $profile->toFilePointer();
        $this->assertIsResource($fp);
    }

    public function testSize(): void
    {
        $profile = new Profile();
        $this->assertEquals(0, $profile->size());

        $profile = new Profile('foo');
        $this->assertEquals(3, $profile->size());
    }

    public function testSave(): void
    {
        $profile = new Profile('foo');
        $path = __DIR__ . '/profile_' . strval(hrtime(true)) . '.test';

        try {
            $profile->save($path);
            $this->assertFileExists($path);
            $this->assertEquals('foo', file_get_contents($path));
        } finally {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
