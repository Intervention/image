<?php
use Intervention\Image\Commands\PsrResponseCommand;
use PHPUnit\Framework\TestCase;

class PsrResponseCommandTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testResponseCreationAndHeaders()
    {
        //We know for sure that mimetype will be "application/xml"
        $encodedContent = '<?xml version="1.0" encoding="UTF-8"?>';

        $image = Mockery::mock('Intervention\Image\Image');
        $stream = \GuzzleHttp\Psr7\stream_for($encodedContent);

        $image->shouldReceive('stream')
            ->with('jpg', 87)
            ->once()
            ->andReturn($stream);

        $image->shouldReceive('getEncoded')
            ->twice()
            ->andReturn($encodedContent);

        $command = new PsrResponseCommand(['jpg', 87]);
        $result = $command->execute($image);

        $this->assertTrue($result);
        $this->assertTrue($command->hasOutput());

        $output = $command->getOutput();

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $output);

        /**
         * @var \Psr\Http\Message\ResponseInterface $output
         */
        $this->assertEquals($stream, $output->getBody());
        $this->assertEquals($encodedContent, (string)$output->getBody());

        $this->assertTrue($output->hasHeader('Content-Type'));
        $this->assertTrue($output->hasHeader('Content-Length'));

        $this->assertTrue(in_array($output->getHeaderLine('Content-Type'), [
            'application/xml',
            'text/xml',
        ]));

        $this->assertEquals(
            strlen($encodedContent),
            $output->getHeaderLine('Content-Length')
        );
    }
}
