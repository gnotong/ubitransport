<?php

declare(strict_types=1);

namespace App\Tests\Unit;

class MarkTest extends BaseKernelTestCase
{
    public function testValidStudent()
    {
        $this->assertHasErrors($this->getMark(), 'create:mark',0);
    }

    public function testInvalidStudent()
    {
        // NotBlank and Length
        $this->assertHasErrors(
            $this->getMark()->setSubject(""),
            'create:mark',
            2
        );
    }

    public function testInvalidValue()
    {
        // Range(0, 20)
        $this->assertHasErrors(
            $this->getMark()->setValue(21),
            'create:mark',
            1
        );
    }

    public function testInvalidStudentNull()
    {
        $this->assertHasErrors(
            $this->getMark()->setStudent(null),
            'create:mark',
            1
        );
    }
}
