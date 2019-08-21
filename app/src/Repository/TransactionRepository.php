<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * @inheritDoc
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->qb($criteria, $orderBy)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->qb($criteria, $orderBy, $limit, $offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return QueryBuilder
     */
    protected function qb(array $criteria, array $orderBy = null, $limit = null, $offset = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t');

        foreach ($criteria as $field => $value) {
            $qb->where("t.{$field} = :val")
                ->setParameter('val', $value);
        }

        if ($orderBy && is_array($orderBy)) {
            foreach ($orderBy as $field => $order) {
                $qb->orderBy('t.' . $field, $order);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->select('t.id transactionId, t.amount *' .
            Transaction::AMOUNT_TO_FLOAT. ' amount, DATE(t.created) date');
    }

    /**
     * @param \DateTime $created
     * @return float
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalAmount(\DateTime $created): float
    {
        $qb = $this->createQueryBuilder('t');
        return $qb->select('SUM(t.amount) *' . Transaction::AMOUNT_TO_FLOAT)
            ->where('t.created = :created')
            ->setParameter('created', $created)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
    }
}
