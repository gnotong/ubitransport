<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Student;
use App\Service\StudentService;
use Symfony\Component\Routing\Annotation\Route;

class StudentMarkAverage
{
    private StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * @Route(
     *     name="students_get_average",
     *     path="/api/students/{id}/avg",
     *     methods={"GET"},
     *     defaults={"_api_resource_class"=Student::class, "_api_item_operation_name"="students"}
     * )
     */
    public function __invoke(Student $data): Student
    {
        return $data;
    }

    public function average(Student $data): array
    {
        return [
            $data,
            [
                'averageMark' => $this->studentService->getStudentAverageMarks($data),
                'numberOfSubjects' => $data->getMarks()->count()
            ]
        ];
    }

}
