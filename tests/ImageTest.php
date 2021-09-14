<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway;

use Laminas\Diactoros\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;
use Spiral\Storage\Storage;
use Spiral\Writeaway\Database\Image;
use Spiral\Writeaway\Repository\ImageRepository;

class ImageTest extends TestCase
{
    use HttpTrait;

    private const FILENAME = 'image.png';
    private const PATH     = __DIR__ . '/files/';

    public function testBadUpload(): void
    {
        $response = $this->upload($this->uri('writeaway:images:upload'), []);
        $this->assertSame(400, $response->getStatusCode());
    }

    public function testUpload(): void
    {
        $response = $this->upload($this->uri('writeaway:images:upload'), ['image' => $this->file()]);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(1, $this->images()->select());

        /** @var Image|null $image */
        $image = $this->images()->findOne();
        $this->assertInstanceOf(Image::class, $image);

        $this->assertTrue($this->storage()->exists($image->original));
        $this->assertSame($this->filesize(), $this->storage()->getSize($image->original));

        $this->storage()->delete($image->thumbnail);
    }

    /**
     * @depends testUpload
     */
    public function testBadDelete(): void
    {
        $response = $this->post($this->uri('writeaway:images:delete'));
        $this->assertSame(400, $response->getStatusCode());
    }

    /**
     * @depends testUpload
     */
    public function testDelete(): void
    {
        $this->upload($this->uri('writeaway:images:upload'), ['image' => $this->file()]);

        /** @var Image|null $image */
        $image = $this->images()->findOne();
        $response = $this->post($this->uri('writeaway:images:delete'), ['id' => $image->id]);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(0, $this->images()->select());

        $this->storage()->delete($image->original);
        $this->assertFalse($this->storage()->exists($image->original));
    }

    /**
     * @depends testUpload
     */
    public function testInvalidDelete(): void
    {
        $response = $this->post($this->uri('writeaway:images:delete'), ['id' => 12345]);
        $this->assertSame(400, $response->getStatusCode());
    }

    /**
     * @depends testUpload
     * @throws \JsonException
     */
    public function testList(): void
    {
        $response = $this->post($this->uri('writeaway:images:list'));
        $output = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertEmpty($output['data']);

        $this->upload($this->uri('writeaway:images:upload'), ['image' => $this->file()]);
        $response = $this->post($this->uri('writeaway:images:list'));
        $output = json_decode($response->getBody()->__toString(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertCount(1, $output['data']);

        /** @var Image|null $image */
        $image = $this->images()->findOne();
        $this->storage()->delete($image->original);
        $this->storage()->delete($image->thumbnail);
    }

    private function file(): UploadedFileInterface
    {
        return new UploadedFile(
            self::PATH . self::FILENAME,
            $this->filesize(),
            0,
            self::FILENAME
        );
    }

    private function filesize(): int
    {
        return filesize(self::PATH . self::FILENAME);
    }

    private function images(): ImageRepository
    {
        return $this->app->get(ImageRepository::class);
    }

    private function storage(): Storage
    {
        return $this->app->get(Storage::class);
    }
}
