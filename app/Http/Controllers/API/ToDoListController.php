<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ToDoList;
use App\Models\ToDoListItem;
use App\Models\User;
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
        $toDoListItems = $request->deals;

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'msg' => 'FAILED, NO USER ' . $userId . ' FOUND',
            ]);
        }

        $createdList = $toDoListService->createToDoList($name, $user, $toDoListItems);

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
    public function createToDoListItem(Request $request, ToDoListService $toDoListService): JsonResponse
    {
        $name = $request->dealName;
        $userId = $request->userId;
        $listId = $request->listId;

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'msg' => 'FAILED, NO USER ' . $userId . ' FOUND',
            ]);
        }

        $toDoList = ToDoList::find($listId);
        if ($toDoList) {
            $createdToDoListItem = $toDoListService->createToDoListItem($name, $user, $toDoList);

            return response()->json([
                'msg' => 'OK',
                'data' => [
                    'dealName' => $createdToDoListItem->name,
                    'dealId' => $createdToDoListItem->id,
                ]
            ]);
        }

        return response()->json([
            'msg' => 'FAILED, NO LIST ' . $listId . ' FOUND',
        ]);

    }

    /**
     * @param Request $request
     * @param ToDoListService $toDoListService
     * @param ImageService $imageService
     * @return JsonResponse
     */
    public function removeToDoListItem(
        Request $request,
        ToDoListService $toDoListService,
        ImageService $imageService
    ): JsonResponse
    {
        $toDoListItemId = $request->dealId;
        $toDoListItem = ToDoListItem::find($toDoListItemId);
        if ($toDoListItem) {
            if ($toDoListItem->image) {
                $imageService->removeImage($toDoListItem);
            }
            $toDoListService->removeToDoListItem($toDoListItem);

            return response()->json([
                'msg' => 'OK',
                'data' => [
                    'dealId' => $toDoListItemId,
                ]
            ]);
        }

        return response()->json([
            'msg' => 'FAIL',
            'data' => [
                'dealId' => $toDoListItemId,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @param ToDoListService $toDoListService
     * @return JsonResponse
     */
    public function updateToDoListItem(Request $request, ToDoListService $toDoListService): JsonResponse
    {
        $newName = $request->newName;
        $toDoListItemId = $request->dealId;
        $toDoListItem = ToDoListItem::find($toDoListItemId);
        if ($toDoListItem) {
            $toDoListService->updateToDoListItem($newName, $toDoListItem);
            return response()->json([
                'msg' => 'OK',
                'data' => [
                    'dealId' => $toDoListItemId,
                    'dealName' => $newName,
                ]
            ]);
        }

        return response()->json([
            'msg' => 'FALIED',
            'data' => [
                'dealId' => $toDoListItemId,
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
