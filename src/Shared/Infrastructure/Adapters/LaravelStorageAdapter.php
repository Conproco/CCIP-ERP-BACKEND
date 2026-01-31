<?php

namespace Src\Shared\Infrastructure\Adapters;

use Src\Shared\Application\Interfaces\FileStorageInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaravelStorageAdapter implements FileStorageInterface
{
    public function store(UploadedFile $file, string $directory, string $filename): string
    {
        Storage::putFileAs($directory, $file, $filename);
        return $filename;
    }

    public function storeBinary(string $content, string $path): bool
    {
        return Storage::put($path, $content);
    }

    public function delete(string $path): bool
    {
        if ($this->exists($path)) {
            return Storage::delete($path);
        }
        return false;
    }

    public function get(string $path): BinaryFileResponse
    {
        $fullPath = Storage::path($path);

        if (!file_exists($fullPath)) {
            abort(404, 'Archivo no encontrado');
        }

        ob_end_clean();
        return response()->file($fullPath);
    }

    public function download(string $path, ?string $downloadName = null): BinaryFileResponse
    {
        $fullPath = Storage::path($path);

        if (!file_exists($fullPath)) {
            abort(404, 'Archivo no encontrado');
        }

        $filename = $downloadName ?? basename($path);
        ob_end_clean();
        return response()->download($fullPath, $filename);
    }

    public function exists(string $path): bool
    {
        return Storage::exists($path);
    }

    public function rename(string $oldPath, string $newPath): bool
    {
        if (!$this->exists($oldPath)) {
            return false;
        }

        return Storage::move($oldPath, $newPath);
    }

    public function generateFilename(string $baseName, string $kind, string $extension): string
    {
        return Str::slug($baseName, '') . "_{$kind}_" . uniqid('', true) . '.' . $extension;
    }
}