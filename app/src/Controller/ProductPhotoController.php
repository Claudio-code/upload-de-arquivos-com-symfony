<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\ProductPhotoException;
use App\Service\RegisterProductPhotosService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_photo_")
 */
class ProductPhotoController extends AbstractController
{
	private RegisterProductPhotosService $registerProductPhotosService;

	public function __construct(RegisterProductPhotosService $registerProductPhotosService)
	{
		$this->registerProductPhotosService = $registerProductPhotosService;
	}

	/**
	 * @Route("/{id}/photo", name="index", methods={"GET"})
	 * @param Product $product
	 * @return JsonResponse
	 */
	public function index(Product $product): JsonResponse
	{
		return $this->json($product->getPhotos()->toArray());
	}

	/**
	 * @Route("/photos", name="create", methods={"POST"})
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function create(Request $request): JsonResponse
	{
		$photos = $request->files->get('photos', []);
		$productId = $request->get('product_id', null);
		
		try {
			if (!$productId) {
				throw new ProductPhotoException(
					'Id do produto não enviado.',
					401
				);
			}
			$this->registerProductPhotosService->execute([...$photos], intval($productId));

			return $this->json([
				'message' => 'upload success.'
			], 201);
		} catch (ProductPhotoException $productPhotoException) {
			return $this->json([
				'error' => $productPhotoException->getMessage()
			], $productPhotoException->getCode());
		} catch (\Exception $exception) {
			return $this->json([
				'error' => $exception->getMessage()
			], $exception->getCode());
		}
	}

	/**
	 * @Route("/{productId}/photo/{photoId}", name="remove", methods={"DELETE"})
	 * @param Product $product
	 * @return JsonResponse
	 */
	public function remove(Product $product): JsonResponse
	{
		return $this->json([
			'message' => 'Welcome to your new controller!'
		]);
	}
}