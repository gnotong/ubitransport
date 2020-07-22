<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();

        $objectSets = $loader->loadFiles([
            __DIR__ . '/../../fixtures/student.yaml',
            __DIR__ . '/../../fixtures/mark.yaml',
        ])->getObjects();

        foreach($objectSets as $object)
        {
            $manager->persist($object);
        }

        $manager->flush();
    }
}
