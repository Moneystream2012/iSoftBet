<?php

namespace App\Resource\Pagination\Transaction;

use App\Resource\Filtering\Transaction\TransactionResourceFilter;
use App\Resource\Filtering\ResourceFilterInterface;
use App\Resource\Pagination\AbstractPagination;
use App\Resource\Pagination\PaginationInterface;

class TransactionPagination
    extends AbstractPagination
    implements PaginationInterface
{
    private const ROUTE = 'getAllTransactionsFiltered';

    /**
     * @var TransactionResourceFilter
     */
    private $resourceFilter;

    /**
     * TransactionPagination constructor.
     * @param TransactionResourceFilter $resourceFilter
     */
    public function __construct(TransactionResourceFilter $resourceFilter)
    {
        $this->resourceFilter = $resourceFilter;
    }

    /**
     * @return ResourceFilterInterface
     */
    public function getResourceFilter(): ResourceFilterInterface
    {
        return $this->resourceFilter;
    }

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return self::ROUTE;
    }
}
