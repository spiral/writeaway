<?php

declare(strict_types=1);

namespace Spiral\Writeaway\Service;

use Cycle\ORM\EntityManagerInterface;
use Psr\Http\Message\UploadedFileInterface;
use Spiral\Files\FilesInterface;
use Spiral\Helpers\Strings;
use Spiral\Storage\Storage;
use Spiral\Writeaway\Config\WriteawayConfig;
use Spiral\Writeaway\Database\Image;
use Spiral\Writeaway\Repository\ImageRepository;

class Images
{
    private const SEED_LENGTH = 8;

    public function __construct(
        private WriteawayConfig $config,
        private ImageRepository $imageRepository,
        private Storage $storage,
        private FilesInterface $files,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function list(): array
    {
        return array_map(
            static fn (Image $image): array => $image->pack(),
            $this->imageRepository->select()->fetchAll()
        );
    }

    /**
     * @throws \ImagickException
     * @throws \Throwable
     */
    public function upload(UploadedFileInterface $file): Image
    {
        $imagick = new \Imagick();
        $imagick->readImageBlob($file->getStream()->__toString());

        $image = new Image();
        $image->width = $imagick->getImageWidth();
        $image->height = $imagick->getImageHeight();
        $image->size = (int)$file->getSize();
        $image->calcRatio();
        $image->thumbnail = $this->createThumbnail($file, $this->createName($file, 'min'));
        $image->original = $this->createOriginal($file, $this->createName($file));

        $this->entityManager->persist($image);
        $this->entityManager->run();

        return $image;
    }

    /**
     * @param Image $image
     * @throws \Throwable
     */
    public function delete(Image $image): void
    {
        $bucket = $this->config->imageStorage();
        $this->storage->bucket($bucket)->delete($image->original);
        $this->storage->bucket($bucket)->delete($image->thumbnail);

        //todo track pieces with images

        $this->entityManager->delete($image);
        $this->entityManager->run();
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

        return $this->storage->bucket($this->config->imageStorage())->write($filename, $tempFile)->getId();
    }

    private function createOriginal(UploadedFileInterface $file, string $filename): string
    {
        return $this->storage->bucket($this->config->imageStorage())->write($filename, $file->getStream())->getId();
    }

    private function createName(UploadedFileInterface $file, string $postfix = ''): string
    {
        return sprintf(
            '%s/%s-%s-%s.%s',
            date('Y-m'),
            Strings::slug(Strings::random(self::SEED_LENGTH)),
            Strings::slug(pathinfo($file->getClientFilename(), PATHINFO_FILENAME)),
            $postfix,
            pathinfo($file->getClientFilename(), PATHINFO_EXTENSION)
        );
    }
}
