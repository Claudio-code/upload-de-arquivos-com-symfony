<?php

namespace App\Controller;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

trait Paginator
{
    private function mountLink(Pagerfanta $pagerfanta, string $routeName, RouterInterface $router): array
    {
        $links['_links'] = [
            'self' => $this->generateLink($routeName, $router, [], $pagerfanta->getCurrentPage()),
            'first' => $this->generateLink($routeName, $router, [], 1),
            'last' => $this->generateLink($routeName, $router, [], $pagerfanta->getNbPages()),
        ];

        if ($pagerfanta->hasNextPage()) {
            $links['_links']['prev'] = $pagerfanta->getPreviousPage();
        }

        if ($pagerfanta->hasNextPage()) {
            $links['_links']['next'] = $pagerfanta->getNextPage();
        }

        return $links;
    }

    private function generateLink(
        string $routeName,
        RouterInterface $router,
        array $routeParams = [],
        int $page = 1
    ): string {
        return $router->generate($routeName, $routeParams, $page);
    }

    private function paginate(array $queryBuilder, Request $request, string $route): array
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
        ];
    }
}
