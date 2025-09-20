<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classes\StoreClassRequest;
use App\Http\Requests\Classes\UpdateClassRequest;
use App\Http\Requests\Classes\UpdateStudyPlanRequest;
use App\Http\Resources\Classes\ClassResource;
use App\Services\ClassService;
use Illuminate\Http\JsonResponse;

class ClassController extends BaseController
{
    public function __construct(private ClassService $service) {}

    public function index(): JsonResponse
    {
        $classes = $this->service->getAllClasses();
        return $this->sendResponse(ClassResource::collection($classes));
    }

    public function show(int $id): JsonResponse
    {
        $class = $this->service->getClassWithStudents($id);

        if ($class === null) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse(new ClassResource($class));
    }

    public function studyPlan(int $id): JsonResponse
    {
        $classWithStudyPlan = $this->service->getClassStudyPlan($id);

        if ($classWithStudyPlan === null) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse(new ClassResource($classWithStudyPlan));
    }

    public function updateStudyPlan(UpdateStudyPlanRequest $request, int $id): JsonResponse
    {
        $class = $this->service->updateClassStudyPlan($id, $request->input('lectures'));

        if ($class === false) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse(
            new ClassResource($class),
            'Study plan updated'
        );
    }

    public function store(StoreClassRequest $request): JsonResponse
    {
        $class = $this->service->createClass($request->validated());

        return $this->sendResponse(
            new ClassResource($class),
            'Class created',
            201
        );
    }

    public function update(UpdateClassRequest $request, int $id): JsonResponse
    {
        $class = $this->service->updateClass($id, $request->validated());

        if ($class === null) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse(
            new ClassResource($class),
            'Class updated'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->removeClass($id);

        if ($deleted === false) {
            return $this->sendError('Class Not Found');
        }

        return $this->sendResponse(null, 'Class deleted');
    }
}
