<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\StoreStudentRequest;
use App\Http\Requests\Students\UpdateStudentRequest;
use App\Http\Resources\Students\StudentIndexResource;
use App\Http\Resources\Students\StudentCreateResource;
use App\Http\Resources\Students\StudentShowResource;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;

class StudentController extends BaseController
{

    public function __construct(private StudentService $service) {}

    public function index(): JsonResponse
    {
        $students = $this->service->getAllStudents();
        return $this->sendResponse(StudentIndexResource::collection($students));
    }

    public function show(int $id): JsonResponse
    {
        $student = $this->service->getStudentInfo($id);

        if ($student === null) {
            return $this->sendError('Student Not Found');
        }

        return $this->sendResponse(new StudentShowResource($student));
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = $this->service->createStudent($request->validated());

        return $this->sendResponse(
            new StudentCreateResource($student),
            'Student created',
            201
        );
    }

    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        $student = $this->service->updateStudent($id, $request->validated());

        if ($student === null) {
            return $this->sendError('Student Not Found');
        }

        return $this->sendResponse(
            new StudentCreateResource($student),
            'Student updated'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->removeStudent($id);

        if ($deleted === false) {
            return $this->sendError('Student Not Found');
        }

        return $this->sendResponse(null, 'Student deleted');
    }

}
