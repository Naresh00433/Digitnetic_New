<?php
session_start();
require_once '../pre/db_config.php';

// Check if user is logged in
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    $_SESSION['error'] = 'Please login to continue';
    echo json_encode(['status' => 'error']);
    exit();
}

// Check if user has permission to create/edit categories
if (!in_array('create_category', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to modify categories.';
    echo json_encode(['status' => 'error']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'add':
                $categoryName = $_POST['categoryName'];
                $stmt = $conn->prepare("INSERT INTO blog_category (cat_name, cat_date, timestamp) VALUES (?, ?, ?)");
                $date = date('Y-m-d');
                $timestamp = date('Y-m-d H:i:s');
                if ($stmt->execute([$categoryName, $date, $timestamp])) {
                    $_SESSION['success'] = 'Category added successfully!';
                    $response = ['status' => 'success'];
                } else {
                    $_SESSION['error'] = 'Failed to add category.';
                    $response = ['status' => 'error'];
                }
                break;

            case 'update':
                $categoryId = $_POST['categoryId'];
                $categoryName = $_POST['categoryName'];
                $stmt = $conn->prepare("UPDATE blog_category SET cat_name = ?, timestamp = ? WHERE cat_id = ?");
                $timestamp = date('Y-m-d H:i:s');
                if ($stmt->execute([$categoryName, $timestamp, $categoryId])) {
                    $_SESSION['success'] = 'Category updated successfully!';
                    $response = ['status' => 'success'];
                } else {
                    $_SESSION['error'] = 'Failed to update category.';
                    $response = ['status' => 'error'];
                }
                break;

            default:
                $_SESSION['error'] = 'Invalid action.';
                $response = ['status' => 'error'];
                break;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
        $response = ['status' => 'error'];
    }

    echo json_encode($response);
    exit;
} else {
    $_SESSION['error'] = 'Invalid request.';
    echo json_encode(['status' => 'error']);
    exit();
}
?>