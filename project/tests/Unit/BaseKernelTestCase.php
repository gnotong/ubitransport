<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Student;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseKernelTestCase extends KernelTestCase
{
    public function getStudent(): Student
    {
        return (new Student())
            ->setFirstName("DOE")
            ->setLastName('JOHN')
            ->setBirthDate(new \DateTimeImmutable('1967-11-08'));
    }

    public function getMark(): Mark
    {
        return (new Mark())
            ->setSubject("INFO110")
            ->setValue(17)
            ->setStudent($this->getStudent());
    }

    public function assertHasErrors(Object $student, string $validationGroup, int $nbError = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')
            ->validate($student, null, [$validationGroup]);

        $this->assertCount($nbError, $errors);
    }

}
