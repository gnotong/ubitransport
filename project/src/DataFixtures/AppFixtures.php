<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\Throwable\LoadingThrowable;

class AppFixtures extends Fixture
{
    /**
     * @throws LoadingThrowable
     */
    public function load(ObjectManager $manager): void
    {
        $loader = new NativeLoader();
        $loader->loadFile(__DIR__ . '/student.yaml');
    }
}
