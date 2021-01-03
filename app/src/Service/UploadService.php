<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
	private string $directory;
	
	public function __construct(string $directory)
	{
		$this->directory = $directory;
	}
	
	public function createNewFileName(UploadedFile $file): string
	{
		return sha1($file->getClientOriginalName()) . uniqid() . '.' . $file->guessExtension();
	}

	public function execute(array $files): array
	{
		$uploadFiles = [];
		foreach ($files as $file) {
			if (!$file instanceof UploadedFile) {
				continue;
			}

			$fileName = $this->createNewFileName($file);
			$uploadFiles[] = $fileName;
			$file->move($this->directory, $fileName);
		}
		
		return $uploadFiles;
	}
}