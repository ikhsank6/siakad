<?php

namespace App\Repositories;

use App\Models\Media;
use App\Repositories\Contracts\MediaRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    private const STORAGE_DISK = 'public';

    public function __construct(Media $model)
    {
        parent::__construct($model);
    }

    /**
     * Upload and create a media record.
     */
    public function upload(UploadedFile $file, string $directory): Media
    {
        return DB::transaction(function () use ($file, $directory) {
            $filename = $file->store($directory, self::STORAGE_DISK);

            return $this->model->create([
                'original_filename' => $file->getClientOriginalName(),
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        });
    }

    /**
     * Delete media record and its file.
     */
    public function deleteMedia(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $media = $this->findOrFail($id);

            if ($media->filename && Storage::disk(self::STORAGE_DISK)->exists($media->filename)) {
                Storage::disk(self::STORAGE_DISK)->delete($media->filename);
            }

            return $media->delete();
        });
    }

    /**
     * Sync media from a path.
     */
    public function syncFromPath(?string $path, ?int $oldMediaId = null): ?Media
    {
        if (! $path) {
            if ($oldMediaId) {
                $this->deleteMedia($oldMediaId);
            }

            return null;
        }

        // If it's already a media path that hasn't changed, return the old one
        if ($oldMediaId) {
            $oldMedia = $this->find($oldMediaId);
            if ($oldMedia && $oldMedia->filename === $path) {
                return $oldMedia;
            }
            // If path changed, delete old one
            $this->deleteMedia($oldMediaId);
        }

        // Create new media from path
        if (Storage::disk(self::STORAGE_DISK)->exists($path)) {
            return $this->model->create([
                'original_filename' => basename($path),
                'filename' => $path,
                'size' => Storage::disk(self::STORAGE_DISK)->size($path),
                'mime_type' => File::mimeType(Storage::disk(self::STORAGE_DISK)->path($path)),
            ]);
        }

        return null;
    }
}
