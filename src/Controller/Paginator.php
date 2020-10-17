<?php

namespace App\Controller;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

trait Paginator
{
    public function paginate(array $queryBuilder, Request $request, string $route): array
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
