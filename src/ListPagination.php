<?php

namespace Ifedko\DoctrineDbalPagination;

use Doctrine\DBAL\Connection;
use Ifedko\DoctrineDbalPagination\DbAdapter;
use Ifedko\DoctrineDbalPagination\ListBuilder;

class ListPagination
{
    const DEFAULT_LIMIT = 20;
    const DEFAULT_OFFSET = 0;

    /**
     * @var \Ifedko\DoctrineDbalPagination\ListBuilder
     */
    private $listQueryBuilder;

    /**
     * @var callable|null
     */
    private $pageItemsMapCallback;

    /**
     * @param \Ifedko\DoctrineDbalPagination\ListBuilder $listQueryBuilder
     */
    public function __construct(ListBuilder $listQueryBuilder)
    {
        $this->listQueryBuilder = $listQueryBuilder;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function get(int $limit, int $offset): array
    {
        $limit = ($limit > 0) ? $limit : self::DEFAULT_LIMIT;
        $offset = ($offset >= 0) ? $offset : self::DEFAULT_OFFSET;

        $pageItems = $this->listQueryBuilder->query()
            ->setMaxResults($limit)->setFirstResult($offset)->execute()->fetchAllAssociative();

        return [
            'total' => $this->listQueryBuilder->totalQuery()
                ->execute()->rowCount(),

            'items' => is_null($this->pageItemsMapCallback) ?
                $pageItems : array_map($this->pageItemsMapCallback, $pageItems),

            'sorting' => $this->listQueryBuilder->sortingParameters()
        ];
    }

    public function definePageItemsMapCallback($callback): void
    {
        $this->pageItemsMapCallback = $callback;
    }
}
