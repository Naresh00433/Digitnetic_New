<?php
session_start();
require_once '../pre/db_config.php';

// Check if user has permission
if (!isset($_SESSION['permissions']) || !in_array('manage_users', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to edit users.';
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Add debug logging
        error_log('POST data: ' . print_r($_POST, true));
        
        $stmt = $conn->prepare("UPDATE blog_users SET 
            first_name = ?, 
            last_name = ?, 
            email = ?, 
            role_id = ?, 
            status = ?, 
            updated_at = NOW() 
            WHERE id = ?");
            
        $params = [
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['role_id'],
            $_POST['status'],  // Make sure this matches the select field name in the form
            $_POST['user_id']
        ];
        
        // Add debug logging
        error_log('SQL Parameters: ' . print_r($params, true));
        
        $stmt->execute($params);

        if($stmt->rowCount() > 0) {
            $_SESSION['success'] = 'User updated successfully!';
        } else {
            $_SESSION['warning'] = 'No changes were made.';
        }
    } catch(PDOException $e) {
        error_log('SQL Error: ' . $e->getMessage());
        $_SESSION['error'] = 'Error updating user: ' . $e->getMessage();
    }
    
    header('Location: ../manage-users.php');
    exit();
}