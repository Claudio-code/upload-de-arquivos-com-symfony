<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    private string $directory;

    private array $allowedFiles = [];

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function setAllowedFiles(array $allowedFiles): void
    {
        $this->allowedFiles = $allowedFiles;
    }

    public function execute(array $files): array
    {
        $uploadFiles = [];
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile || !in_array($file->guessExtension(), $this->allowedFiles)) {
                continue;
            }

            $fileName = $this->createNewFileName($file);
            $uploadFiles[] = $fileName;
            $file->move($this->directory, $fileName);
        }

        return $uploadFiles;
    }

    private function createNewFileName(UploadedFile $file): string
    {
        return sha1($file->getClientOriginalName()).uniqid().'.'.$file->guessExtension();
    }
}