<?php

declare(strict_types=1);

namespace Spiral\WriteAway\Controller;

use Spiral\Http\Exception\ClientException\ServerErrorException;
use Spiral\Logger\Traits\LoggerTrait;
use Spiral\Router\Annotation\Route;
use Spiral\WriteAway\Requests\ImageRequest;
use Spiral\WriteAway\Service\Images;

class ImageController
{
    use LoggerTrait;

    private Images $images;

    public function __construct(Images $images)
    {
        $this->images = $images;
    }

    /**
     * @Route(name="writeAway:images:list", group="writeAway", methods={"GET", "POST"}, route="images/list")
     * @return array
     */
    public function list(): array
    {
        return [
            'status' => 200,
            'images' => $this->images->list(),
        ];
    }

    /**
     * @Route(name="writeAway:images:upload", group="writeAway", methods="POST", route="images/upload")
     * @param ImageRequest $request
     * @return array
     */
    public function uploadAction(ImageRequest $request): array
    {
        if (!$request->isValid()) {
            return [
                'status' => 400,
                'errors' => $request->getErrors(),
            ];
        }

        try {
            $image = $this->images->upload($request->image);
        } catch (\Throwable $exception) {
            $this->getLogger('default')->error('Image upload failed', compact('exception'));
            throw new ServerErrorException('Image upload failed', $exception);
        }

        return [
            'status' => 200,
            'image'  => $image->pack(),
        ];
    }
}
