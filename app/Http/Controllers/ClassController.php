<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classes\StoreClassRequest;
use App\Http\Requests\Classes\UpdateClassRequest;
use App\Http\Requests\Classes\UpdateStudyPlanRequest;
use App\Services\ClassService;
use Illuminate\Http\JsonResponse;

class ClassController extends BaseController
{
    public function __construct(private ClassService $service) {}

    public function index(): JsonResponse
    {
        $classes = $this->service->getAllClasses();

        return $this->sendResponse($classes);
    }

    public function show(int $id): JsonResponse
    {
        $class = $this->service->getClassWithStudents($id);

        if (!$class) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse($class);
    }

    public function studyPlan(int $id): JsonResponse
    {
        $data = $this->service->getClassStudyPlan($id);

        if (!$data) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse($data);
    }

    public function updateStudyPlan(UpdateStudyPlanRequest $request, int $id): JsonResponse
    {
        $updated = $this->service->updateClassStudyPlan($id, $request->input('lectures'));

        if (!$updated) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse(null, 'Study plan updated');
    }

    public function store(StoreClassRequest $request): JsonResponse
    {
        $data = $this->service->createClass($request->validated());

        return $this->sendResponse($data, 'Class created', 201);
    }

    public function update(UpdateClassRequest $request, int $id): JsonResponse
    {
        $data = $this->service->updateClass($id, $request->validated());

        if (!$data) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse($data, 'Class updated');
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->removeClass($id);
        if (!$deleted) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse(null, 'Class deleted');
    }
}
