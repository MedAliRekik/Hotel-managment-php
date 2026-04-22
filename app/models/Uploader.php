<?php

declare(strict_types=1);

class Uploader
{
    private string $targetDir;
    private int $maxFileSize;
    private array $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    public function __construct(string $targetDir, int $maxFileSize)
    {
        $this->targetDir = rtrim($targetDir, '/') . '/';
        $this->maxFileSize = $maxFileSize;

        if (!is_dir($this->targetDir)) {
            mkdir($this->targetDir, 0777, true);
        }
    }

    public function upload(array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Please select a valid image.'];
        }

        if (($file['size'] ?? 0) > $this->maxFileSize) {
            return ['success' => false, 'message' => 'Image is too large.'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $this->allowedTypes, true)) {
            return ['success' => false, 'message' => 'Only JPG, PNG or WEBP images are allowed.'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('room_', true) . '.' . strtolower($extension);
        $destination = $this->targetDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'message' => 'Image upload failed.'];
        }

        return ['success' => true, 'filename' => $filename];
    }
}
