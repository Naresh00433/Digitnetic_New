<?php
session_start();
require_once '../pre/db_config.php';

// Function to sanitize input data (only for email)
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize email only, leave password as-is
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password']; // Raw password for verification
    $csrf_token = sanitizeInput($_POST['csrf_token']);

    // Validate CSRF token
    if ($csrf_token !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = 'Invalid CSRF token';
        header('Location: ../');
        exit();
    }

    try {
        // Check user credentials and status
        $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, role_id, status FROM blog_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['status'] !== 'Active') {
                $_SESSION['error'] = 'Your account is not active. Please contact the administrator.';
                header('Location: ../');
                exit();
            }

            if (password_verify($password, $user['password'])) {
                // Password is correct
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role_id'];
                $_SESSION['is_login'] = true;

                // Fetch permissions
                $stmt = $conn->prepare("SELECT p.permission_name FROM permissions p
                                      JOIN role_permissions rp ON p.id = rp.permission_id
                                      WHERE rp.role_id = ?");
                $stmt->execute([$user['role_id']]);
                $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $_SESSION['permissions'] = $permissions;

                $_SESSION['success'] = 'Welcome ' . $_SESSION['user_name'];
                header('Location: ../dashboard.php');
                exit();
            } else {
                $_SESSION['error'] = 'Invalid email or password';
                header('Location: ../');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: ../');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error occurred';
        header('Location: ../');
        exit();
    }
} else {
    header('Location: ../');
    exit();
}