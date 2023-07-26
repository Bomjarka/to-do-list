<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @param Request $request
     * @param TagService $tagService
     * @return JsonResponse
     */
    public function createTag(Request $request, TagService $tagService): JsonResponse
    {
        $tagName = $request->tagName;
        $createdTag = $tagService->createTag($tagName);

        return response()->json([
           'msg' => 'OK',
           'data' => [
               'tagName' => $createdTag->name,
               'tagId' => $createdTag->id,
           ],
        ]);
    }

    /**
     * @param Request $request
     * @param TagService $tagService
     * @return JsonResponse
     */
    public function updateTag(Request $request, TagService $tagService): JsonResponse
    {
        $tagId = $request->tagId;
        $newName = $request->newName;

        $tag = Tag::find($tagId);
        $tagService->updateTag($tag, $newName);
        if ($tag) {
            return response()->json([
                'msg' => 'OK',
                'data' => [
                    'tagName' => $tag->name,
                    'tagId' => $tagId,
                ],
            ]);
        }

        return response()->json([
            'msg' => 'FAILED Tag ' . $tagId . ' NOT FOUND',
        ]);
    }

    public function removeTag(Request $request, TagService $tagService)
    {
        $tagId = $request->tagId;
        $tag = Tag::find($tagId);
        if ($tag) {
            $tagService->removeTag($tag);

            return response()->json([
                'msg' => 'OK',
                'data' => [
                    'tagId' => $tagId,
                ],
            ]);
        }

        return response()->json([
            'msg' => 'FAILED Tag ' . $tagId . ' NOT FOUND',
        ]);
    }
}
