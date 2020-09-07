<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\ProductException;
use App\Repository\ProductRepository;
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
     * @Route("/{id}", name="update", methods={"PATCH", "PUT"})
     * @param Product $product
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(Product $product, Request $request): JsonResponse
    {
        $data = $request->request->all();

        try {
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setContent($data['content']);
            $product->setPrice($data['price']);
            $product->setSlug($data['slug']);
            $product->setUpdatedAt(
                new \DateTime("now", new \DateTimeZone('America/Sao_Paulo'))
            );

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->json([
                'message' => 'Produto atualizado',
                'product' => $product
            ]);
        } catch (ProductException $productException) {
            return $this->json([
                'error' => $productException
            ]);
        }
    }

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
            $product->setPrice($data['price']);
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
           return $this->json([
                'error' => $productException
           ]);
        }
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    public function index(ProductRepository $productRepository): JsonResponse
    {
        return $this->json($productRepository->findAll());
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return $this->json($product);
    }

    /**
     * @Route("/{id}", name="remove", methods={"DELETE"})
     * @param Product $product
     * @return JsonResponse
     */
    public function remove(Product $product): JsonResponse
    {
        try {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($product);
            $manager->flush();

            return $this->json([
                'message' => 'Produto removido',
            ]);
        } catch (ProductException $productException) {
            return $this->json([
                'error' => $productException
            ]);
        }
    }
}