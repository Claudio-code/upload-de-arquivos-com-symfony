<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Exception;
use DateTimeZone;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @return JsonResponse
     */
    public function index(CategoryRepository $categoryRepository): JsonResponse
    {
        return $this->json($categoryRepository->findAll());
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return $this->json($category);
    }

    /**
     * @Route("/{id}", name="remove", methods={"DELETE"})
     * @param Category $category
     * @return JsonResponse
     */
    public function remove(Category $category): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();

        return $this->json([
            'message' => 'categoria removida',
        ]);
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        $category->setCreatedAt(
            new DateTime("now", new DateTimeZone('America/Sao_Paulo'))
        );
        $category->setUpdatedAt(
            new DateTime("now", new DateTimeZone('America/Sao_Paulo'))
        );

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($category);
        $doctrine->flush();

        return $this->json([
            'message' => 'Categoria cadastrada com sucesso.'
        ]);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     * @param Category $category
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Category $category, Request $request): JsonResponse
    {
        $data = $request->request->all();
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        $category->setUpdatedAt(
            new DateTime("now", new DateTimeZone('America/Sao_Paulo'))
        );

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->flush();

        return $this->json([
            'message' => 'Categoria atualizada com sucesso.'
        ]);
    }
}
