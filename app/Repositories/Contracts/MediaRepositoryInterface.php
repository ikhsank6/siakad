<?php

namespace App\Repositories\Contracts;

use App\Models\Media;
use Illuminate\Http\UploadedFile;

interface MediaRepositoryInterface extends RepositoryInterface
{
    /**
     * Upload and create a media record.
     */
    public function upload(UploadedFile $file, string $directory): Media;

    /**
     * Delete media record and its file.
     */
    public function deleteMedia(int $id): bool;

    /**
     * Sync media from a path.
     */
    public function syncFromPath(?string $path, ?int $oldMediaId = null): ?Media;
}
