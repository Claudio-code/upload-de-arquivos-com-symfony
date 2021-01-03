<?php

namespace App\Service;

use App\Exception\ProductPhotoException;
use App\Repository\ProductPhotoRepository;
use App\Repository\ProductRepository;
use App\Entity\ProductPhoto;
use DateTime;
use DateTimeZone;

class RegisterProductPhotosService
{
	private UploadService $uploadService;
	
	private ProductPhotoRepository $productPhotoRepository;
	
	private ProductRepository $productRepository;
	
	public function __construct(
		UploadService $uploadService,
		ProductPhotoRepository $productPhotoRepository,
		ProductRepository $productRepository
	) {
		$this->uploadService = $uploadService;
		$this->productRepository = $productRepository;
		$this->productPhotoRepository = $productPhotoRepository;
		$this->uploadService->setAllowedFiles(['png', 'jpg']);
	}

	public function execute(array $photos, int $productId): void
	{
		$photosNames = $this->uploadService->execute($photos);
		$product = $this->productRepository->find($productId);
		if (!$product) {
			throw new ProductPhotoException(
				'Produto não encontrado.',
				401
			);
		}
		
		foreach ($photosNames as $name) {
			$productPhoto = new ProductPhoto();
			$productPhoto->setImage($name);
			$productPhoto->setProduct($product);
			
			if (!$productPhoto->getCreatedAt()) {
				$productPhoto->setCreatedAt(
					new DateTime('now', new DateTimeZone('America/Sao_Paulo'))
				);
			}
			$this->productPhotoRepository->runSync($productPhoto);
		}
	}
}