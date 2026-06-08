<?php
/**
 * FileUploader — handles secure profile image uploads.
 *
 * - Validates MIME type & extension (whitelist)
 * - Enforces max size
 * - Generates a safe, unique filename
 */

declare(strict_types=1);

class FileUploader
{
    private string $uploadDir;
    private int $maxSize = 2 * 1024 * 1024; // 2 MB

    /** @var array<string,string> Allowed MIME => extension */
    private array $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    private string $error = '';

    public function __construct(string $uploadDir = __DIR__ . '/../uploads/')
    {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Handle an uploaded file from the $_FILES array.
     *
     * @param array<string,mixed> $file A single $_FILES entry.
     * @return string|null The stored filename, or null if no file/failure.
     */
    public function upload(array $file): ?string
    {
        // No file uploaded — that's allowed (image is optional).
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->error = 'File upload failed. Please try again.';
            return null;
        }

        if ($file['size'] > $this->maxSize) {
            $this->error = 'Image must be smaller than 2 MB.';
            return null;
        }

        // Verify the real MIME type, not just the client-supplied one.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!isset($this->allowed[$mime])) {
            $this->error = 'Only JPG, PNG, GIF and WEBP images are allowed.';
            return null;
        }

        $extension = $this->allowed[$mime];
        $filename = 'student_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->error = 'Could not save the uploaded file.';
            return null;
        }

        return $filename;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function hasError(): bool
    {
        return $this->error !== '';
    }
}
