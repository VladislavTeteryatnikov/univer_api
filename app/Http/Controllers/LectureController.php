<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lectures\StoreLectureRequest;
use App\Http\Requests\Lectures\UpdateLectureRequest;
use App\Http\Resources\Lectures\LectureIndexResource;
use App\Http\Resources\Lectures\LectureShowResource;
use App\Services\LectureService;
use Illuminate\Http\JsonResponse;

class LectureController extends BaseController
{
    public function __construct(private LectureService $service){}

    public function index(): JsonResponse
    {
        $lectures = $this->service->getAllLectures();
        return $this->sendResponse(LectureIndexResource::collection($lectures));
    }

    public function show(int $id): JsonResponse
    {
        $lecture = $this->service->getLectureWithDetails($id);

        if ($lecture === null) {
            return $this->sendError('Lecture Not Found');
        }

        return $this->sendResponse(new LectureShowResource($lecture));

    }

    public function store(StoreLectureRequest $request): JsonResponse
    {
        $lecture = $this->service->createLecture($request->validated());

        return $this->sendResponse(
            new LectureShowResource($lecture),
            'Lecture created',
            201
        );
    }

    public function update(UpdateLectureRequest $request, int $id): JsonResponse
    {
        $lecture = $this->service->updateLecture($id, $request->validated());

        if ($lecture === null) {
            return $this->sendError('Lecture Not Found');
        }

        return $this->sendResponse(
            new LectureShowResource($lecture),
            'Lecture updated'
        );
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
