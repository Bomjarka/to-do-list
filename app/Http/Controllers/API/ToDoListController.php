<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ToDoListItem;
use App\Services\ImageService;
use App\Services\ToDoListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToDoListController extends Controller
{
    private const ACCEPTED_FILES = ['jpeg', 'png'];

    /**
     * @param Request $request
     * @param ToDoListService $toDoListService
     * @return JsonResponse
     */
    public function createList(Request $request, ToDoListService $toDoListService): JsonResponse
    {
        $name = $request->listName;
        $userId = $request->userId;
        $deals = $request->deals;
        $createdList = $toDoListService->createToDoList($name, $userId, $deals);

        return response()->json([
            'msg' => 'OK',
            'data' => [
                'listId' => $createdList->id,
                'listName' => $createdList->name,
                'dealsCount' => $createdList->listItems()->count(),
                'created' => $createdList->created_at
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param ToDoListService $toDoListService
     * @return JsonResponse
     */
    public function createDeal(Request $request, ToDoListService $toDoListService): JsonResponse
    {
        $name = $request->dealName;
        $userId = $request->userId;
        $listId = $request->listId;
        $createdDeal = $toDoListService->createDeal($name, $userId, $listId);

        return response()->json([
            'msg' => 'OK',
            'data' => [
                'dealName' => $createdDeal->name,
                'dealId' => $createdDeal->id,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param ToDoListService $toDoListService
     * @return JsonResponse
     */
    public function removeDeal(Request $request, ToDoListService $toDoListService): JsonResponse
    {
        $dealId = $request->dealId;
        $toDoListService->removeDeal($dealId);
        return response()->json([
            'msg' => 'OK',
            'data' => [
                'dealId' => $dealId,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param ToDoListService $toDoListService
     * @return JsonResponse
     */
    public function updateDeal(Request $request, ToDoListService $toDoListService): JsonResponse
    {
        $newName = $request->newName;
        $dealId = $request->dealId;
        $toDoListService->updateDeal($newName, $dealId);
        return response()->json([
            'msg' => 'OK',
            'data' => [
                'dealId' => $dealId,
                'dealName' => $newName,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param ImageService $imageService
     * @return JsonResponse|void
     */
    public function addImage(Request $request, ImageService $imageService)
    {
        $dealId = $request->dealId;
        $deal = ToDoListItem::find($dealId);
        if ($deal) {
            $fileData = $request->fileData['file'];
            [, $data] = explode(';', $fileData);
            [, $data] = explode(',', $data);
            $fileData = base64_decode($data);
            $fileMimeType = finfo_buffer(finfo_open(), $fileData, FILEINFO_MIME_TYPE);

            $fileType = '';
            if ($fileMimeType === 'image/jpeg' || $fileMimeType === 'image/jpg') {
                $fileType = 'jpeg';
            } elseif ($fileMimeType === 'image/png') {
                $fileType = 'png';
            }
            if (in_array($fileType, self::ACCEPTED_FILES)) {
                $fileName = Str::random() . '.' . $fileType;
                if ($deal->image) {
                    $imageService->updateImage($fileName, $fileData, $deal);
                } else {
                    $imageService->createImage($fileName, $fileData, $deal);
                }
            }

            return response()->json([
                'msg' => 'OK',
            ]);
        }
    }
}
