<?php


namespace App\Service;

use App\Entity\ProductPhoto;

class RegisterProductPhotosService
{
	private ProductPhoto $productPhoto;
	
	private UploadService $uploadService;
	
	public function __construct(ProductPhoto $productPhoto, string $directory)
	{
		$this->productPhoto = $productPhoto;
		$this->uploadService = new UploadService($directory);
	}
	
	public function execute(array $photos): void
	{
		$photosNames = $this->uploadService->execute($photos);
		
		
	}
}