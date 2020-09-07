<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\ProductException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="products_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->request->all();

        try {
            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setContent($data['content']);
            $product->setPrice(intval($data['price']));
            $product->setSlug($data['slug']);
            $product->setIsActive(true);
            $product->setCreatedAt(
                new \DateTime("now", new \DateTimeZone('America/Sao_Paulo'))
            );
            $product->setUpdatedAt(
                new \DateTime("now", new \DateTimeZone('America/Sao_Paulo'))
            );

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            return $this->json([
                'message' => 'Produto cadastrado',
                'product' => $product
            ]);
        } catch (ProductException $productException) {
            $this->json([
                'error' => $productException
            ]);
        }
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
}