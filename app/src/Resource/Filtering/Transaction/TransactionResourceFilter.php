<?php

namespace App\Resource\Filtering\Transaction;

use App\Repository\TransactionRepository;
use App\Resource\Filtering\ResourceFilterInterface;
use Doctrine\ORM\QueryBuilder;

class TransactionResourceFilter implements ResourceFilterInterface
{
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * TransactionResourceFilter constructor.
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param TransactionFilterDefinition $filter
     * @return QueryBuilder
     */
    public function getResources($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter);
        $qb->select('t');

        return $qb;
    }

    /**
     * @param TransactionFilterDefinition $filter
     * @return QueryBuilder
     */
    public function getResourceCount($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter, 'count');
        $qb->select('count(t)');

        return $qb;
    }

    /**
     * @param TransactionFilterDefinition $filter
     * @param null|string $count
     * @return QueryBuilder
     */
    public function getQuery(TransactionFilterDefinition $filter, ?string $count = null): QueryBuilder
    {
        $qb = $this->transactionRepository->createQueryBuilder('t');

        if (null !== $filter->getCustomerId()) {
            $qb->where(
                $qb->expr()->eq('t.customerId', ':customerId')
            );
            $qb->setParameter('customerId', $filter->getCustomerId());
        }

        if (null !== $filter->getAmountFrom()) {
            $qb->andWhere(
                $qb->expr()->gte('t.amount', ':amountFrom')
            );
            $qb->setParameter('amountFrom', $filter->getAmountFrom());
        }

        if (null !== $filter->getAmountTo()) {
            $qb->andWhere(
                $qb->expr()->lte('t.amount', ':amountTo')
            );
            $qb->setParameter('amountTo', $filter->getAmountTo());
        }

        if (null !== $filter->getDateFrom()) {
            $qb->andWhere(
                $qb->expr()->gte('t.created', ':dateFrom')
            );
            $qb->setParameter('dateFrom', new \DateTime($filter->getDateFrom()));
        }

        if (null !== $filter->getDateTo()) {
            $qb->andWhere(
                $qb->expr()->lte('t.created', ':dateTo')
            );
            $qb->setParameter('dateTo', new \DateTime($filter->getDateTo()));
        }

        if (null !== $filter->getSortByArray() && $count === null) {
            foreach ($filter->getSortByArray() as $by => $order) {
                $expr = 'desc' == $order
                    ? $qb->expr()->desc("t.$by")
                    : $qb->expr()->asc("t.$by");
                $qb->addOrderBy($expr);
            }
        }

        return $qb;
    }

}
