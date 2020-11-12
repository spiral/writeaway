<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Controller;

use Spiral\Http\Exception\ClientException\ServerErrorException;
use Spiral\Logger\Traits\LoggerTrait;
use Spiral\WriteAway\Database\Image;
use Spiral\WriteAway\Repository\ImageRepository;
use Spiral\WriteAway\Request\Image\ImageRequest;
use Spiral\WriteAway\Request\Image\UploadImageRequest;
use Spiral\WriteAway\Service\Images;

class ImageController
{
    use LoggerTrait;

    private Images $images;

    public function __construct(Images $images)
    {
        $this->images = $images;
    }

    public function list(): array
    {
        return [
            'status' => 200,
            'data'   => $this->images->list(),
        ];
    }

    public function upload(UploadImageRequest $request): array
    {
        try {
            // todo multiple image upload
            $image = $this->images->upload($request->image);
        } catch (\Throwable $exception) {
            $this->getLogger('default')->error('Image upload failed', compact('exception'));
            throw new ServerErrorException('Image upload failed', $exception);
        }

        return [
            'status' => 200,
            'data'   => [$image->pack()],
        ];
    }

    public function delete(ImageRequest $request, ImageRepository $imageRepository): array
    {
        $image = $imageRepository->findByPK($request->id);
        if (!$image instanceof Image) {
            throw new \RuntimeException('Image not found.');
        }

        try {
            $this->images->delete($image);
        } catch (\Throwable $exception) {
            $this->getLogger('default')->error(
                'Image delete failed',
                ['exception' => $exception, 'image' => $image->pack()]
            );
            throw new ServerErrorException('Image delete failed', $exception);
        }

        return ['status' => 200];
    }
}
