<?php

namespace Services\Media;

class MediaManager {
    // Media server URL for uploading files
    //private const MEDIA_SERVER_URL = 'http://localhost:8000';
    private const MEDIA_SERVER_URL = '/uploads';
    private string $uploadDirectory;

    public function __construct(string $uploadDirectory = self::MEDIA_SERVER_URL) {
        if ($uploadDirectory === '/uploads') {
            $uploadDirectory = rtrim($_SERVER['DOCUMENT_ROOT'] . self::MEDIA_SERVER_URL, '/');
        }
        $this->uploadDirectory = rtrim($uploadDirectory, '/');
    }

    /**
     * Uploads a file to the specified directory.
     * Creates the directory if it doesn't exist.
     * Returns the file path if successful or null if an error occurs.
     *
     * @param array $file The file to upload, typically from $_FILES.
     * @param string $destination The destination path where the file should be saved.
     *
     * @return string|null The path to the uploaded file or null on failure.
     */
    public function uploadFile(array $file, string $destination): ?string {
        if (!isset($file['tmp_name']) || !file_exists($file['tmp_name'])) {
            return null;
        }

        // Ensure base upload directory exists
        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0755, true);
        }

        // Create the destination directory if it doesn't exist
        $fullDestinationDir = $this->uploadDirectory . '/' . dirname($destination);
        if (!is_dir($fullDestinationDir)) {
            mkdir($fullDestinationDir, 0755, true);
        }

        // Full destination path
        $destinationPath = $this->uploadDirectory . '/' . $destination;

        // If the file is an actual uploaded file, use move_uploaded_file
        if (is_uploaded_file($file['tmp_name'])) {
            $success = move_uploaded_file($file['tmp_name'], $destinationPath);
        } else {
            // For manually generated temp files (like compressed images), use rename()
            $success = rename($file['tmp_name'], $destinationPath);
        }

        return $success ? $destinationPath : null;
    }

    /**
     * Get the full path of a file in the upload directory.
     *
     * @param string $fileName
     * @return string
     */
    public function getFilePath(string $fileName): string {
        return $this->uploadDirectory . '/' . $fileName;
    }

    /**
     * Get the contents of a file in the upload directory.
     *
     * @param string $fileName
     * @return string|null
     */
    public function getFile(string $fileName): ?string {
        $path = $this->getFilePath($fileName);
        return is_file($path) ? file_get_contents($path) : null;
    }

    /**
     * Get a list of all files in the upload directory.
     *
     * @return array
     */
    public function getFiles(): array {
        if (!is_dir($this->uploadDirectory)) {
            return [];
        }
        $files = scandir($this->uploadDirectory);
        return $files ? array_diff($files, ['.', '..']) : [];
    }

    /**
     * Get a list of all files in a user's folder.
     *
     * @param string $userId
     * @return array
     */
    public function getUserFiles(string $userId): array {
        $userDir = $this->uploadDirectory . '/' . $userId;
        if (!is_dir($userDir)) {
            return [];
        }
        $files = scandir($userDir);
        return $files ? array_diff($files, ['.', '..']) : [];
    }

    /**
     * Get a list of all files in a user's subdirectory.
     * (e.g., profile_pics or posts)
     *
     * @param string $userId
     * @param string $subDir
     * @return array
     */
    public function getUserFolderDirectory(string $userId, string $subDir): array {
        $dirPath = $this->uploadDirectory . '/' . $userId . '/' . $subDir;
        if (!is_dir($dirPath)) {
            return [];
        }
        $files = scandir($dirPath);
        return $files ? array_diff($files, ['.', '..']) : [];
    }
}
