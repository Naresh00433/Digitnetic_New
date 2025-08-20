<?php
session_start();
require_once '../pre/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $title = $_POST['title'];
        $content = $_POST['content'];
        $metaTitle = $_POST['metaTitle'];
        $description = $_POST['description'];
        $metaDescription = $_POST['metaDescription'];
        $category = !empty($_POST['category']) ? $_POST['category'] : 1; // Default to 1 if not provided
        $slug = $_POST['slug'];
        $status = $_POST['status'];
        $publishedBy = 1; // Replace with actual user ID from session

        // Validate category if provided
        if ($category !== null) {
            $stmt = $conn->prepare("SELECT cat_id FROM blog_category WHERE cat_id = ?");
            $stmt->execute([$category]);
            if ($stmt->rowCount() === 0) {
                throw new Exception('Invalid category selected');
            }
        }

        // Handle featured image upload
        $featuredImage = '';
        if (isset($_FILES['featuredImage']) && $_FILES['featuredImage']['error'] === 0) {
            // Create upload directory if it doesn't exist
            $uploadDir = '../../blog-upload/images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate filename based on title
            $fileExtension = strtolower(pathinfo($_FILES['featuredImage']['name'], PATHINFO_EXTENSION));
            $sanitizedTitle = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
            $sanitizedTitle = trim($sanitizedTitle, '-');
            $fileName = substr($sanitizedTitle, 0, 50) . '-' . time() . '.' . $fileExtension;
            $targetPath = $uploadDir . $fileName;

            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($fileExtension, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.');
            }

            if (move_uploaded_file($_FILES['featuredImage']['tmp_name'], $targetPath)) {
                // Store only the filename without path
                $featuredImage = $fileName;
            } else {
                throw new Exception('Failed to upload image.');
            }
        }

        // Insert post
        $sql = "INSERT INTO blog_posts (title, slug, content, meta_title, description, 
                meta_description, featured_image, cat_id, published_by, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $title, $slug, $content, $metaTitle, $description, 
            $metaDescription, $featuredImage, $category, $publishedBy, $status
        ]);

        $postId = $conn->lastInsertId();
        
        // Set session message based on status
        $_SESSION['success'] = $status === 'published' 
            ? 'Post published successfully! Redirecting to all posts...' 
            : 'Draft saved successfully! Redirecting to all posts...';

        $response = [
            'status' => 'success',
            'redirect' => 'all-posts.php'
        ];

    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
        $response = [
            'status' => 'error'
        ];
    }

    echo json_encode($response);
    exit;
}
?>
