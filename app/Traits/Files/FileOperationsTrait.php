<?php

namespace App\Traits\Files;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait FileOperationsTrait
{

  /************************************************************************* */

    public function uploadFile(UploadedFile $file, string $path, string $disk = 'public'): string
    {
        $filename = $this->generateCustomFileName($file);
        $filePath = $file->storeAs($path, $filename, $disk);

        return $filePath;
    }
 /************************************************************************* */

    public function delete($filePath, string $disk = 'public')
    {
        if (!Storage::disk($disk)->exists($filePath)) {
            throw new NotFoundHttpException();
        }

        return Storage::disk($disk)->delete($filePath);
    }

 /************************************************************************* */
    private function generateCustomFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(10) . '_' . time() . '.' . $extension;

        return $filename;
    }



}
