<?php
session_start();
require_once '../pre/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $postId = $_POST['postId'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $metaTitle = $_POST['metaTitle'];
        $description = $_POST['description'];
        $metaDescription = $_POST['metaDescription'];
        $category = !empty($_POST['category']) ? $_POST['category'] : 1;
        $slug = $_POST['slug'];
        $status = $_POST['status'];

        // Verify post exists
        $stmt = $conn->prepare("SELECT featured_image FROM blog_posts WHERE post_id = ?"); // Changed id to post_id
        $stmt->execute([$postId]);
        $existingPost = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$existingPost) {
            throw new Exception('Post not found');
        }

        // Validate category
        if ($category !== null) {
            $stmt = $conn->prepare("SELECT cat_id FROM blog_category WHERE cat_id = ?");
            $stmt->execute([$category]);
            if ($stmt->rowCount() === 0) {
                throw new Exception('Invalid category selected');
            }
        }

        // Handle featured image upload
        $featuredImage = $existingPost['featured_image']; // Keep existing image by default
        if (isset($_FILES['featuredImage']) && $_FILES['featuredImage']['error'] === 0) {
            $uploadDir = '../../blog-upload/images/';
            
            // Delete old image if exists
            if (!empty($existingPost['featured_image'])) {
                $oldImagePath = $uploadDir . $existingPost['featured_image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload new image
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
                $featuredImage = $fileName;
            } else {
                throw new Exception('Failed to upload image.');
            }
        }

        // Update post
        $sql = "UPDATE blog_posts SET 
                title = ?, 
                slug = ?, 
                content = ?, 
                meta_title = ?, 
                description = ?, 
                meta_description = ?, 
                featured_image = ?, 
                cat_id = ?, 
                status = ?,
                updated_at = CURRENT_TIMESTAMP
                WHERE post_id = ?"; // Changed id to post_id
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $title, $slug, $content, $metaTitle, $description, 
            $metaDescription, $featuredImage, $category, $status, $postId
        ]);

        $_SESSION['success'] = 'Post updated successfully!';
        
        $response = [
            'status' => 'success',
            'redirect' => 'all-posts.php'
        ];

    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }

    echo json_encode($response);
    exit;
}
?>