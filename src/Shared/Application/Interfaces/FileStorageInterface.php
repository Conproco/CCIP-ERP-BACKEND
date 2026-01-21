<?php

namespace Src\Shared\Application\Interfaces;

use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface FileStorageInterface
{

    public function store(UploadedFile $file, string $directory, string $filename): string;


    public function storeBinary(string $content, string $path): bool;


    public function delete(string $path): bool;

    public function get(string $path): BinaryFileResponse;

    public function download(string $path, ?string $downloadName = null): BinaryFileResponse;

    public function exists(string $path): bool;

    public function rename(string $oldPath, string $newPath): bool;

    public function generateFilename(string $baseName, string $kind, string $extension): string;
}