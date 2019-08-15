<?php

namespace App\Service;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionService
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * TransactionService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $customerId
     * @param int $amount
     * @return Transaction
     */
    public function createTransaction(int $customerId, int $amount): Transaction
    {
        $transactionEntity = (new Transaction())
            ->setCustomerId($customerId)
            ->setAmount($amount);

        $this->em->persist($transactionEntity);
        $this->em->flush();

        return $transactionEntity;
    }

    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Transaction::class)->findBy([], ['id' => 'DESC']);
    }
}
