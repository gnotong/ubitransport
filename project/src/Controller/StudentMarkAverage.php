<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Student;

class StudentMarkAverage
{
    public function __invoke(Student $data): Student
    {
        //Calculate student average marks

//        $data['average_mark'] = 15;

        return $data;
    }

}
