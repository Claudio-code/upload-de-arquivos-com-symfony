<?php

namespace App\Service;

use App\Entity\ProductPhoto;
use App\Exception\ProductPhotoException;
use App\Repository\ProductPhotoRepository;

class RemoveFileService
{
    private ProductPhotoRepository $productPhotoRepository;

    private string $directory;

    public function __construct(ProductPhotoRepository $productPhotoRepository, string $directory)
    {
        $this->productPhotoRepository = $productPhotoRepository;
        $this->directory = $directory;
    }

    public function execute(ProductPhoto $productPhoto): void
    {
        $filePath = "{$this->directory}/{$productPhoto->getImage()}";
        if (!file_exists($filePath)) {
            throw new ProductPhotoException(
                'Arquivo não encontrado.',
                401
            );
        }

        unlink($filePath);
        $this->productPhotoRepository->runDelete($productPhoto);
    }
}