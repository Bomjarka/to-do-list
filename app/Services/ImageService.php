<?php

namespace App\Services;

use App\Models\ListItemImage;
use App\Models\ToDoListItem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * @param string $fileName
     * @param string $fileData
     * @param ToDoListItem $deal
     * @return void
     */
    public function createImage(string $fileName, string $fileData, ToDoListItem $deal): void
    {
        Storage::disk('images')->put($fileName, $fileData);
        $imagePath = Storage::disk('images')->path($fileName);
        $previewImageName = $this->createPreview($imagePath);

        $this->saveListImage($fileName, $previewImageName, $deal->id);
    }

    /**
     * @param string $fileName
     * @param string $fileData
     * @param ToDoListItem $deal
     * @return void
     */
    public function updateImage(string $fileName, string $fileData, ToDoListItem $deal): void
    {
        $this->removeImage($deal);
        $this->createImage($fileName, $fileData, $deal);
    }

    /**
     * @param string $filePath
     * @return string
     */
    private function createPreview(string $filePath): string
    {
        $image = Image::make($filePath);
        $image->resize(150, 150);
        $image->save(Storage::disk('images')->path('/') . 'preview-' . $image->basename);

        return $image->basename;
    }

    /**
     * @param string $fileName
     * @param string $previewImageName
     * @param int $dealId
     * @return void
     */
    private function saveListImage(string $fileName, string $previewImageName, int $dealId): void
    {
        ListItemImage::create([
            'name' => $fileName,
            'preview_name' => $previewImageName,
            'list_item_id' => $dealId,
        ]);
    }

    /**
     * @param ToDoListItem $deal
     * @return void
     */
    public function removeImage(ToDoListItem $toDoListItem): void
    {
        Storage::disk('images')->delete($toDoListItem->image->name);
        Storage::disk('images')->delete($toDoListItem->image->preview_name);
        $toDoListItem->image->delete();
    }
}
