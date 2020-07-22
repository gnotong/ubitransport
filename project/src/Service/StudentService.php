<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Mark;
use App\Entity\Student;
use App\Repository\StudentRepository;

class StudentService
{
    private StudentRepository $repository;

    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieves the class room average mark and the number of student
     * @return array
     */
    public function getClassRoomStats (): array
    {
        $averageRoomMark  = $this->getRoomAverageMark();
        $numberOfStudents = $this->repository->count([]);

        return compact('averageRoomMark', 'numberOfStudents');
    }


    /**
     * Computes the average mark of a student based, on the number of subjects he(she) is reading
     * @return float
     */
    public function getStudentAverageMarks(Student $student): float
    {
        $nbMarks = $student->getMarks()->count();

        if ($nbMarks === 0) {
            return 0;
        }

        $total = $this->sumStudentMark($student);

        return round($total / $nbMarks, 2);
    }

    /**
     * Computes the average mark of all the students
     * @return float
     */
    public function getRoomAverageMark(): float
    {
        $students = $this->repository->findAll();
        $nbStudents = count($students);

        if (!$nbStudents) {
            return 0;
        }

        $sum = array_reduce(
            $students,
            fn($total, Student $student) => $total + $this->getStudentAverageMarks($student)
        );

        if(!$sum) {
            return 0;
        }

        return round($sum / $nbStudents, 2);
    }

    /**
     * Sum all marks for a student
     * @return int
     */
    public function sumStudentMark(Student $student): int
    {
        $marks = $student->getMarks();

        $sum = array_reduce(
            $marks->toArray(),
            fn($total, Mark $mark) => $total + $mark->getValue()
        );

        if(!$sum) {
            return 0;
        }

        return $sum;
    }
}
