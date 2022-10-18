<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        UserFactory::createOne(['email'=>'user@test.de']);
        UserFactory::createOne([
            'email'=>'admin@test.de',
            'roles' =>['ROLE_ADMIN']

            ]);
        UserFactory::createMany(10);
        $manager->flush();
    }
}
