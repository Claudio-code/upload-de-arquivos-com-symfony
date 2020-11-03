<?php


namespace App\Service;


use App\Entity\ProductPhoto;

class RegisterProductPhotosService
{
	public ProductPhoto $productPhoto;
	
	public function __construct(ProductPhoto $productPhoto)
	{
		$this->productPhoto = $productPhoto;
	}
	
	public function execute(array $photos): void
	{
		
	}
}