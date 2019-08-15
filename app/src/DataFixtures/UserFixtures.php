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
        $userEntity = new User();
        $userEntity->setLogin('admin');
        $userEntity->setPlainPassword('admin');
        $userEntity->setRoles(['ROLE_ADMIN']);
        $manager->persist($userEntity);
        $manager->flush();
    }
}
