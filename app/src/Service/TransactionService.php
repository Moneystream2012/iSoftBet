<?php

namespace App\Service;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var TransactionRepository */
    private $transactionRepository;

    /**
     * TransactionService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, TransactionRepository $transactionRepository)
    {
        $this->em = $em;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param int $customerId
     * @param float $amount
     * @return Transaction
     */
    public function createTransaction(int $customerId, float $amount): Transaction
    {
        $transactionEntity = (new Transaction())
            ->setCustomerId($customerId)
            ->setAmount($amount);

        $this->em->persist($transactionEntity);
        $this->em->flush();
        return $transactionEntity;
    }

    /**
     * @param int $transactionId
     * @param float $amount
     * @return Transaction
     */
    public function updateTransaction(int $transactionId, float $amount): Transaction
    {
        $transactionEntity = $this->transactionRepository->find($transactionId)
            ->setAmount($amount);

        $this->em->persist($transactionEntity);
        $this->em->flush();
        return $transactionEntity;
    }

    /**
     * @param int $transactionId
     * @return bool
     */
    public function deleteTransaction($transactionId): bool
    {
        $transactionEntity = $this->transactionRepository->find($transactionId);

        if (!$transactionEntity) {
            return false;
        }

        $this->em->remove($transactionEntity);
        $this->em->flush();
        return true;
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return object|null
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->transactionRepository->findOneBy($criteria);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
    {
        $orderBy = $orderBy ?? ['id' => 'DESC'];

        return $this->transactionRepository->findBy($criteria, $orderBy, $limit, $offset);
    }
}
