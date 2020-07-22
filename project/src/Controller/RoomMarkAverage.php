<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\StudentService;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomMarkAverage
{
    private StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse($this->studentService->getClassRoomStats());
    }
}
