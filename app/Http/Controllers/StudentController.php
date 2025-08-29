<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\StoreStudentRequest;
use App\Http\Requests\Students\UpdateStudentRequest;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;

class StudentController extends BaseController
{

    public function __construct(private StudentService $service){}

    public function index(): JsonResponse
    {
        $students = $this->service->getAllStudents();
        return $this->sendResponse($students);
    }

    public function show(int $id): JsonResponse
    {
        $student = $this->service->getStudentInfo($id);

        if (!$student) {
            return $this->sendError('Student Not Found');
        }

        return $this->sendResponse($student);
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $data = $this->service->createStudent($request->validated());

        return $this->sendResponse($data, 'Student created', 201);
    }

    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        $data = $this->service->updateStudent($id, $request->validated());

        if (!$data) {
            return $this->sendError('Student Not Found');
        }

        return $this->sendResponse($data, 'Student updated');
    }

    public function destroy(int $id)
    {
        $deleted = $this->service->removeStudent($id);

        if (!$deleted) {
            return $this->sendError('Student Not Found');
        }

        return $this->sendResponse(null, 'Student deleted');
    }

}
