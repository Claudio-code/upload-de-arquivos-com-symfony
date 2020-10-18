<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\ProductException;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\PaginatorService;
use DateTime;
use DateTimeZone;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/product", name="products_")
 */
class ProductController extends AbstractController
{
    use ErrorsValidateEntity;

    private PaginatorService $paginatorService;

    public function __construct(PaginatorService $paginatorService)
    {
        $this->paginatorService = $paginatorService;
    }

    /**
     * @Route("/{id}", name="update", methods={"PATCH", "PUT"})
     *
     * @throws Exception
     */
    public function update(Product $product, Request $request): JsonResponse
    {
        $data = $request->request->all();
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);
        $product->setUpdatedAt(
            new DateTime('now', new DateTimeZone('America/Sao_Paulo'))
        );

        try {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->json([
                'message' => 'Produto atualizado',
                'product' => $product,
            ]);
        } catch (ProductException $productException) {
            return $this->json([
                'error' => $productException,
            ]);
        }
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     *
     * @throws Exception
     */
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->request->all();
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        if (!$form->isValid()) {
            $errors = [];

            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }

            return $this->json($errors);
        }

        $product->setIsActive(true);
        $product->setCreatedAt(
            new DateTime('now', new DateTimeZone('America/Sao_Paulo'))
        );
        $product->setUpdatedAt(
            new DateTime('now', new DateTimeZone('America/Sao_Paulo'))
        );

        // $errors = $this->validate($validator, $product);
        // if ($errors) {
        //     return $this->json(['errors' => $errors]);
        // }

        try {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();

            return $this->json([
                'message' => 'Produto cadastrado',
                'product' => $product,
            ]);
        } catch (ProductException $productException) {
            return $this->json([
                'error' => $productException,
            ]);
        }
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(
        ProductRepository $productRepository,
        Request $request
    ): JsonResponse {
        $fields = $request->query->get('fields', false);
        $filters = $request->query->get('filters', null);
        $limit = $request->query->get('limit', false);

        $products = $productRepository->getProductsByFilters($filters, $limit, $fields);
        $productsResult = $this->paginatorService->execute($products, $request, 'products_index');

        return $this->json($productsResult);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Product $product): JsonResponse
    {
        return $this->json($product);
    }

    /**
     * @Route("/{id}", name="remove", methods={"DELETE"})
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
                'error' => $productException,
            ]);
        }
    }
}
