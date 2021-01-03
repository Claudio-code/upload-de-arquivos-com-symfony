<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductPhoto;
use App\Exception\ProductPhotoException;
use App\Service\RegisterProductPhotosService;
use App\Service\RemoveFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_photo_")
 */
class ProductPhotoController extends AbstractController
{
    private RegisterProductPhotosService $registerProductPhotosService;

    private RemoveFileService $removeFileService;

    public function __construct(
        RegisterProductPhotosService $registerProductPhotosService,
        RemoveFileService $removeFileService
    ) {
        $this->registerProductPhotosService = $registerProductPhotosService;
        $this->removeFileService = $removeFileService;
    }

    /**
     * @Route("/{id}/photo", name="index", methods={"GET"})
     */
    public function index(Product $product): JsonResponse
    {
        return $this->json($product->getPhotosAndPath($this->getParameter('upload_dir')));
    }

    /**
     * @Route("/photos", name="create", methods={"POST"})
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
                'message' => 'upload success.',
            ], 201);
        } catch (ProductPhotoException $productPhotoException) {
            return $this->json([
                'error' => $productPhotoException->getMessage(),
            ], $productPhotoException->getCode());
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ], $exception->getCode());
        }
    }

    /**
     * @Route("/photo/{id}", name="remove", methods={"DELETE"})
     */
    public function remove(ProductPhoto $productPhoto): JsonResponse
    {
        $this->removeFileService->execute($productPhoto);

        return $this->json([
            'message' => 'arquivo removido com sucesso.',
        ]);
    }
}