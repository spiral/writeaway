<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Service;

use Psr\Http\Message\UploadedFileInterface;
use Spiral\Files\FilesInterface;
use Spiral\Helpers\Strings;
use Spiral\Storage\StorageManager;
use Spiral\WriteAway\Config\WriteAwayConfig;
use Spiral\WriteAway\Database\Image;
use Spiral\WriteAway\Repository\ImageRepository;

class Images
{
    private const SEED_LENGTH = 8;

    private WriteAwayConfig $config;
    private ImageRepository $imageRepository;
    private StorageManager $storage;
    private FilesInterface $files;

    public function __construct(
        WriteAwayConfig $config,
        ImageRepository $imageRepository,
        StorageManager $storage,
        FilesInterface $files
    ) {
        $this->config = $config;
        $this->imageRepository = $imageRepository;
        $this->storage = $storage;
        $this->files = $files;
    }

    public function list(): array
    {
        $images = [];
        /** @var Image $image */
        foreach ($this->imageRepository as $image) {
            $images[] = $image->pack();
        }
        return $images;
    }

    /**
     * @param UploadedFileInterface $file
     * @return Image
     * @throws \ImagickException
     */
    public function upload(UploadedFileInterface $file): Image
    {
        $imagick = new \Imagick();
        $imagick->readImageBlob($file->getStream());

        $image = new Image();
        $image->width = $imagick->getImageWidth();
        $image->height = $imagick->getImageHeight();
        $image->size = (int)$file->getSize();
        $image->thumbnail = $this->createThumbnail($file, $this->createName($file, '-min'));
        $image->original = $this->createOriginal($file, $this->createName($file));

        return $image;
    }

    /**
     * @param UploadedFileInterface $file
     * @param string                $filename
     * @return string
     * @throws \ImagickException
     */
    private function createThumbnail(UploadedFileInterface $file, string $filename): string
    {
        $imagick = new \Imagick();
        $imagick->readImageBlob($file->getStream()->__toString());
        $imagick->cropThumbnailImage($this->config->thumbnailWidth(), $this->config->thumbnailHeight());

        $tempFile = $this->files->tempFilename();
        $imagick->writeImage($tempFile);

        return $this->storage->put($this->config->imageStorage(), $filename, $tempFile)->getAddress();
    }

    private function createOriginal(UploadedFileInterface $file, string $filename): string
    {
        return $this->storage->put($this->config->imageStorage(), $filename, $file->getStream())->getAddress();
    }

    protected function createName(UploadedFileInterface $file, string $postfix = ''): string
    {
        return sprintf(
            '%s/%s-%s.%s.%s',
            date('Y-m'),
            Strings::random(self::SEED_LENGTH),
            Strings::slug(pathinfo($file->getClientFilename(), PATHINFO_FILENAME)),
            $postfix,
            pathinfo($file->getClientFilename(), PATHINFO_EXTENSION)
        );
    }
}
