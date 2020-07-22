<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Student;
use App\Repository\StudentRepository;
use App\Service\StudentService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass StudentService
 */
class StudentServiceTest extends TestCase
{
    private StudentService $studentService;

    private  StudentRepository $studentRepository;

    protected function setUp(): void
    {
        $this->studentRepository = $this->getMockBuilder(StudentRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->studentService = new StudentService($this->studentRepository);
    }

    public function testSumStudentMark()
    {
        $student = (new Student())->setFirstName('JOHN')
            ->setLastName('DOE')
            ->setBirthDate(new \DateTimeImmutable('2011-10-10'));

        $marks = [
            ['subject' => 'HIST12', 'value' => 13],
            ['subject' => 'INFO101', 'value' => 15],
            ['subject' => 'ENG102', 'value' => 14],
            ['subject' => 'PHI105', 'value' => 18],
        ];
        $this->addStudentMarks($student, $marks);

        $total = $this->studentService->sumStudentMark($student);

        $this->assertEquals(60, $total);
    }

    public function testSumStudentWithoutMark()
    {
        $student = (new Student())->setFirstName('JOHN')
            ->setLastName('DOE')
            ->setBirthDate(new \DateTimeImmutable('2011-10-10'));

        $marks = [];
        $this->addStudentMarks($student, $marks);

        $total = $this->studentService->sumStudentMark($student);

        $this->assertEquals(0, $total);
    }

    public function testGetStudentAverageMarks()
    {
        $student = (new Student())->setFirstName('JOHN')
            ->setLastName('DOE')
            ->setBirthDate(new \DateTimeImmutable('2011-10-10'));

        $marks = [
            ['subject' => 'HIST12', 'value' => 13],
            ['subject' => 'INFO101', 'value' => 15],
            ['subject' => 'ENG102', 'value' => 14],
            ['subject' => 'PHI105', 'value' => 18],
        ];
        $this->addStudentMarks($student, $marks);

        $total = $this->studentService->getStudentAverageMarks($student);

        $this->assertEquals(15, $total);
    }

    public function testGetStudentAverageWithoutMarks()
    {
        $student = (new Student())->setFirstName('JOHN')
            ->setLastName('DOE')
            ->setBirthDate(new \DateTimeImmutable('2011-10-10'));

        $marks = [];
        $this->addStudentMarks($student, $marks);

        $total = $this->studentService->getStudentAverageMarks($student);

        $this->assertEquals(0, $total);
    }

    public function testGetRoomAverageMark()
    {
        $john = (new Student())->setFirstName('JOHN')
            ->setLastName('DOE')
            ->setBirthDate(new \DateTimeImmutable('2011-10-10'));

        $marks = [
            ['subject' => 'HIST12', 'value' => 13],
            ['subject' => 'INFO101', 'value' => 15],
            ['subject' => 'ENG102', 'value' => 14],
            ['subject' => 'PHI105', 'value' => 18],
        ];
        $this->addStudentMarks($john, $marks);

        $alice = (new Student())->setFirstName('ALICE')
            ->setLastName('BOB')
            ->setBirthDate(new \DateTimeImmutable('2001-01-10'));

        $marks = [
            ['subject' => 'HIST12', 'value' => 11],
            ['subject' => 'INFO101', 'value' => 18],
            ['subject' => 'ENG102', 'value' => 14],
            ['subject' => 'PHI105', 'value' => 14],
        ];
        $this->addStudentMarks($alice,$marks);

        $this->studentRepository->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([$john, $alice]));

        $total = $this->studentService->getRoomAverageMark();

        $this->assertEquals(14.63, $total);
    }

    public function testGetRoomAverageMarkWithoutStudent()
    {
        $this->studentRepository->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([]));

        $total = $this->studentService->getRoomAverageMark();

        $this->assertEquals(0, $total);
    }

    private function addStudentMarks(Student $student, array $marks): void
    {
        foreach ($marks as $mark) {
            $m = (new Mark())->setSubject($mark['subject'])->setValue($mark['value']);
            $student->addMark($m);
        }
    }
}
