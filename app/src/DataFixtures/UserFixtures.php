<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $entity = new User();
        $entity->setLogin('admin');
        $entity->setPlainPassword('admin');
        $entity->setRoles(['ROLE_ADMIN']);
        $manager->persist($entity);
        $manager->flush();
    }
}
