<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use DateTime;
use DateTimeZone;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    use ErrorsValidateEntity;

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): JsonResponse
    {
        return $this->json($categoryRepository->findAll());
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Category $category): JsonResponse
    {
        return $this->json($category);
    }

    /**
     * @Route("/{id}", name="remove", methods={"DELETE"})
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
     *
     * @throws Exception
     */
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->request->all();
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        if ($errors = $this->validate($validator, $category)) {
            return $this->json($errors);
        }

        $category->setCreatedAt(
            new DateTime('now', new DateTimeZone('America/Sao_Paulo'))
        );
        $category->setUpdatedAt(
            new DateTime('now', new DateTimeZone('America/Sao_Paulo'))
        );

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($category);
        $doctrine->flush();

        return $this->json([
            'message' => 'Categoria cadastrada com sucesso.',
        ]);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     *
     * @throws Exception
     */
    public function update(Category $category, Request $request): JsonResponse
    {
        $data = $request->request->all();
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        $category->setUpdatedAt(
            new DateTime('now', new DateTimeZone('America/Sao_Paulo'))
        );

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->flush();

        return $this->json([
            'message' => 'Categoria atualizada com sucesso.',
        ]);
    }
}
