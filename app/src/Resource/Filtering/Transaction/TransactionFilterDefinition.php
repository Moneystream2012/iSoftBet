<?php

namespace App\Resource\Filtering\Transaction;

use App\Resource\Filtering\AbstractFilterDefinition;
use App\Resource\Filtering\FilterDefinitionInterface;
use App\Resource\Filtering\SortTableFilterDefinitionInterface;

class TransactionFilterDefinition
    extends AbstractFilterDefinition
    implements FilterDefinitionInterface, SortTableFilterDefinitionInterface
{
    /**
     * @var int|null
     */
    private $customerId;

    /**
     * @var int|null
     */
    private $amountFrom;

    /**
     * @var int|null
     */
    private $amountTo;

    /**
     * @var string|null
     */
    private $dateFrom;

    /**
     * @var string|null
     */
    private $dateTo;

    /**
     * @var array|null
     */
    private $sortByArray;

    /**
     * @var null|string
     */
    private $sortBy;

    /**
     * TransactionFilterDefinition constructor.
     * @param int|null $customerId
     * @param int|null $amountFrom
     * @param int|null $amountTo
     * @param string|null $dateFrom
     * @param string|null $dateTo
     * @param null|string $sortByQuery
     * @param array|null $sortByArray
     */
    public function __construct(
        ?int $customerId,
        ?int $amountFrom,
        ?int $amountTo,
        ?string $dateFrom,
        ?string $dateTo,
        ?string $sortByQuery,
        ?array $sortByArray
    )
    {
        $this->customerId = $customerId;
        $this->amountFrom = $amountFrom;
        $this->amountTo = $amountTo;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->sortBy = $sortByQuery;
        $this->sortByArray = $sortByArray;
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    /**
     * @return int|null
     */
    public function getAmountFrom(): ?int
    {
        return $this->amountFrom;
    }

    /**
     * @return int|null
     */
    public function getAmountTo(): ?int
    {
        return $this->amountTo;
    }

    /**
     * @return string|null
     */
    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    /**
     * @return string|null
     */
    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }

    /**
     * @return array|null
     */
    public function getSortByArray(): ?array
    {
        return $this->sortByArray;
    }

    /**
     * @return null|string
     */
    public function getSortByQuery(): ?string
    {
        return $this->sortBy;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function getHashKey(): string
    {
        return md5($this->customerId) .
            md5($this->amountFrom) . md5($this->amountTo).
            md5($this->dateFrom) . md5($this->dateTo);
    }
}
