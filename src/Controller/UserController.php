<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UserException;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use DateTimeZone;
use Exception;


/**
 * @Route("/user", name="user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function index(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll());
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return $this->json($user);
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT", "PATCH"})
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update(User $user, Request $request): JsonResponse
    {
        try {
            $userData = $request->request->all();
            $form = $this->createForm(UserType::class, $user);
            $form->submit($userData);
            $user->setUpdatedAt(
                new DateTime("now", new DateTimeZone('America/Sao_Paulo'))
            );
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->json([
                'message' => 'Produto atualizado',
                'product' => $user
            ]);
        } catch (UserException $userException) {
            return $this->json([
                'error' => $userException
            ]);
        }
    }

    /**
     * @Route("/{id}", name="remove", methods={"DELETE"})
     * @param User $user
     * @return JsonResponse
     */
    public function remove(User $user): JsonResponse
    {
        try {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($user);
            $manager->flush();

            return $this->json([
                'message' => 'usuario removido',
            ]);
        } catch (UserException $userException) {
            return $this->json([
                'error' => $userException
            ]);
        }
    }

    /**
     * @Route("/", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $userData = $request->request->all();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($userData);

        $user->setIsActive(true);
        $user->setCreatedAt(
            new DateTime("now", new DateTimeZone('America/Sao_Paulo'))
        );
        $user->setUpdatedAt(
            new DateTime("now", new DateTimeZone('America/Sao_Paulo'))
        );

        try {
            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($user);
            $doctrine->flush();

            return $this->json([
                'message' => 'Cadastrado com sucesso.',
                'user' => $user
            ]);
        } catch (UserException $userException) {
            return $this->json([
                'error' => $userException
            ]);
        }
    }
}
