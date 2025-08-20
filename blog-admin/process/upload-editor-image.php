<?php
session_start();
require_once '../pre/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['upload'])) {
            throw new Exception('No file uploaded');
        }

        $file = $_FILES['upload'];
        $uploadDir = '../../blog-upload/images/editor/';

        // Create upload directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validate file type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($fileExtension, $allowedTypes)) {
            throw new Exception('Invalid file type');
        }

        // Generate unique filename
        $fileName = 'editor-' . uniqid() . '-' . time() . '.' . $fileExtension;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Get the base URL of your application
            $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            $baseUrl = rtrim(str_replace("\\", "/", $baseUrl), '/'); // Clean up the base URL

            // Construct the absolute URL
            $imageUrl = $baseUrl . '/../../blog-upload/images/editor/' . $fileName;

            // Return URL relative to site root
            echo json_encode([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => $imageUrl  // Use absolute URL here
            ]);
        } else {
            throw new Exception('Failed to upload file');
        }

    } catch (Exception $e) {
        echo json_encode([
            'uploaded' => 0,
            'error' => [
                'message' => $e->getMessage()
            ]
        ]);
    }
    exit;
}
?>