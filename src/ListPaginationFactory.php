<?php

namespace Ifedko\DoctrineDbalPagination;

use Doctrine\DBAL\Connection;
use Ifedko\DoctrineDbalPagination\ListPagination;
use Ifedko\DoctrineDbalPagination\Exception\ListPaginationFactoryException;

class ListPaginationFactory
{
    /**
     * @param Connection $dbConnection
     * @param string $listBuilderFullClassName
     * @param array $listParameters
     *
     * @return ListPagination
     * @throws \Exception
     */
    public static function create(Connection $dbConnection, string $listBuilderFullClassName, array $listParameters = []): ListPagination
    {
        if (!class_exists($listBuilderFullClassName)) {
            throw new ListPaginationFactoryException(sprintf('Unknown list builder class %s', $listBuilderFullClassName));
        }

        $listBuilder = new $listBuilderFullClassName($dbConnection);
        $listBuilder->configure($listParameters);

        return new ListPagination($listBuilder);
    }
}
