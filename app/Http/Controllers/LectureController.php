<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lectures\StoreLectureRequest;
use App\Http\Requests\Lectures\UpdateLectureRequest;
use App\Services\LectureService;
use Illuminate\Http\JsonResponse;

class LectureController extends BaseController
{
    public function __construct(private LectureService $service){}

    public function index(): JsonResponse
    {
        $lectures = $this->service->getAllLectures();
        return $this->sendResponse($lectures);
    }

    public function show(int $id): JsonResponse
    {
        $lecture = $this->service->getLectureWithDetails($id);

        if ($lecture === null) {
            return $this->sendError('Lecture Not Found');
        }

        return $this->sendResponse($lecture);

    }

    public function store(StoreLectureRequest $request): JsonResponse
    {
        $data = $this->service->createLecture($request->validated());
        return $this->sendResponse($data, 'Lecture created', 201);
    }

    public function update(UpdateLectureRequest $request, int $id): JsonResponse
    {
        $data = $this->service->updateLecture($id, $request->validated());

        if ($data === null) {
            return $this->sendError('Lecture Not Found');
        }

        return $this->sendResponse($data, 'Lecture updated');
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->service->removeLecture($id);

        if ($deleted === false) {
            return $this->sendError('Lecture Not Found');
        }

        return $this->sendResponse(null,'Lecture deleted');
    }
}
