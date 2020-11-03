<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\UploadService;
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
	private UploadService $uploadService;
	
	public function __construct()
	{
		$this->uploadService = new UploadService($this->getParameter('upload_dir'));
	}

	/**
	 * @Route("/{productId}/photo", name="index", methods={"GET"})
	 * @param Product $product
	 * @return JsonResponse
	 */
    public function index(Product $product): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!'
        ]);
    }

	/**
	 * @Route("/photos", name="create", methods={"POST"})
	 * @param Request $request
	 * @return JsonResponse
	 */
    public function create(Request $request): JsonResponse
	{
		$photos = $request->files->get('photos', []);
		$this->uploadService->execute([...$photos]);
		
		return $this->json([ 'message' => 'upload sucess.' ]);
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