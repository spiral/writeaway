<?php

declare(strict_types=1);

namespace Spiral\Tests\Writeaway;

use Laminas\Diactoros\UploadedFile;
use Psr\Http\Message\UploadedFileInterface;
use Spiral\Storage\StorageManager;
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

        $original = $this->storage()->open($image->original);
        $this->assertTrue($original->exists());
        $this->assertSame($this->filesize(), $original->getSize());

        $original->delete();
        $this->storage()->open($image->thumbnail)->delete();
    }

    public function testBadDelete(): void
    {
        $response = $this->post($this->uri('writeaway:images:delete'));
        $this->assertSame(400, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->upload($this->uri('writeaway:images:upload'), ['image' => $this->file()]);

        /** @var Image|null $image */
        $image = $this->images()->findOne();
        $response = $this->post($this->uri('writeaway:images:delete'), ['id' => $image->id]);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(0, $this->images()->select());

        $original = $this->storage()->open($image->original);
        $this->assertFalse($original->exists());
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

    private function storage(): StorageManager
    {
        return $this->app->get(StorageManager::class);
    }
}
