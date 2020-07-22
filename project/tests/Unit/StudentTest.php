<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Student;

class StudentTest extends BaseKernelTestCase
{
    public function testValidStudent()
    {
        $this->assertHasErrors($this->getStudent(),'create:student',0);
    }

    public function testInvalidStudent()
    {
        // NotBlank and Length
        $this->assertHasErrors(
            $this->getStudent()->setFirstName(""),
            'create:student',
            2
        );
    }

    public function testInvalidStudentBirthDate()
    {
        $student =(new Student())
            ->setFirstName("ALICE")
            ->setLastName('BOB');

        // NotBlank
        $this->assertHasErrors( $student,'create:student',1);
    }
}
