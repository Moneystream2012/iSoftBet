<?php

namespace App\Service;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;

final class CustomerService
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CustomerService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $name
     * @param float $cnp
     * @return Customer
     */
    public function createCustomer(string $name, float $cnp): Customer
    {
        $customerEntity = (new Customer())
            ->setName($name)
            ->setCnp($cnp);

        $this->em->persist($customerEntity);
        $this->em->flush();

        return $customerEntity;
    }

    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Customer::class)->findBy([], ['id' => 'DESC']);
    }
}
