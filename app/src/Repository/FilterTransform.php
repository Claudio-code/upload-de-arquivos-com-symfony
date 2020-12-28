<?php

namespace App\Repository;

trait FilterTransform
{
    private function transformFields(string $fields, string $tableAlies): string
    {
        $fetchFields = explode(',', $fields);
        $fetchFields = array_map(function (string $line) use ($tableAlies) {
            return "{$tableAlies}.{$line}";
        }, $fetchFields);

        return implode(', ', $fetchFields);
    }

    private function transformFilters(string $filters): array
    {
        $fetchFilters = explode(';', $filters);
        $filtersTransform = [];

        foreach ($fetchFilters as $filter) {
            $filtersTransform[] = explode(':', $filter);
        }

        return $filtersTransform;
    }
}