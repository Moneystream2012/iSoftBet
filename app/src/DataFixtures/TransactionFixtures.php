<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class TransactionFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 30; $i++) {
            $entity = new Transaction();
            $entity->setCustomerId(($i + 1) % 12);
            $entity->setAmount(32.7 * $i);

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
