<?php
session_start();
require_once '../pre/db_config.php';

// Check if user is logged in
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    $_SESSION['error'] = 'Please login to continue';
    header("Location: ../index.php");
    exit();
}

// Check if user has permission to delete posts
if (!in_array('delete_post', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to delete posts.';
    header("Location: ../dashboard.php");
    exit();
}

// Check if post ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = 'Invalid post ID';
    header("Location: ../all-posts");
    exit();
}

try {
    // Begin transaction
    $conn->beginTransaction();

    $post_id = $_GET['id'];

    // First, check if post exists and get featured image path
    $check = $conn->prepare("SELECT post_id, featured_image FROM blog_posts WHERE post_id = ?");
    $check->execute([$post_id]);
    $post = $check->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        throw new Exception('Post not found');
    }

    // Delete the featured image if it exists
    if (!empty($post['featured_image']) && file_exists('../' . $post['featured_image'])) {
        unlink('../' . $post['featured_image']);
    }

    // Delete the post
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE post_id = ?");
    $stmt->execute([$post_id]);

    // Commit transaction
    $conn->commit();
    $_SESSION['success'] = 'Post deleted successfully';

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollBack();
    $_SESSION['error'] = 'Error deleting post: ' . $e->getMessage();
}

// Redirect back to posts list
header("Location: ../all-posts");
exit();