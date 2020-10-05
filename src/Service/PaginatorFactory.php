<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class PaginatorFactory
{
    public function paginate(array $queryBuilder, Request $request, string $route)
    {
        $currentPage = $request->query->get('page', 1);
        $adapter = new ArrayAdapter($queryBuilder);
        $paginator = new Pagerfanta($adapter);

        $paginator
            ->setMaxPerPage(3)
            ->setCurrentPage($currentPage);
        $data = [];

        foreach ($paginator->getCurrentPageResults() as $item) {
            $data[] = $item;
        }

        $paginationResult = [
            'data' => $paginator->getCurrentPageResults(),
            'total' => $paginator->getNbResults()
        ];

        return $paginationResult;
    }
}
