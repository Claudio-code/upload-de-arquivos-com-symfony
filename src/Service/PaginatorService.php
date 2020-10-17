<?php

namespace App\Service;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class PaginatorService
{
    private RouterInterface $routerInterface;

    public function __construct(RouterInterface $routerInterface)
    {
        $this->routerInterface = $routerInterface;
    }

    public function execute(array $queryBuilder, Request $request, string $routeName): array
    {
        $currentPage = $request->query->get('page', 1);
        $adapter = new ArrayAdapter($queryBuilder);
        $paginator = new Pagerfanta($adapter);

        $paginator
            ->setMaxPerPage(3)
            ->setCurrentPage($currentPage)
        ;
        $data = [];

        foreach ($paginator->getCurrentPageResults() as $item) {
            $data[] = $item;
        }

        return [
            'data' => $paginator->getCurrentPageResults(),
            'total' => $paginator->getNbResults(),
            'links' => $this->mountLink($paginator, $routeName, $this->routerInterface),
        ];
    }

    private function mountLink(Pagerfanta $pagerfanta, string $routeName, RouterInterface $router): array
    {
        $links = [
            'self' => $this->generateLink($routeName, $router, [], $pagerfanta->getCurrentPage()),
            'first' => $this->generateLink($routeName, $router, [], 1),
            'last' => $this->generateLink($routeName, $router, [], $pagerfanta->getNbPages()),
        ];

        if ($pagerfanta->hasPreviousPage()) {
            $links['prev'] = $pagerfanta->getPreviousPage();
        }

        if ($pagerfanta->hasNextPage()) {
            $links['next'] = $pagerfanta->getNextPage();
        }

        return $links;
    }

    private function generateLink(
        string $routeName,
        RouterInterface $router,
        array $routeParams = [],
        int $page = 1
    ): string {
        return $router->generate($routeName, [...$routeParams, 'page' => $page]);
    }
}
