    <?php
session_start();
require_once '../pre/db_config.php';

// Check if user has permission
if (!in_array('manage_users', $_SESSION['permissions'])) {
    $_SESSION['error'] = 'Access Denied. You do not have permission to add users.';
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // First check if email already exists
        $checkEmail = $conn->prepare("SELECT COUNT(*) FROM blog_users WHERE email = ?");
        $checkEmail->execute([$_POST['email']]);
        $emailExists = $checkEmail->fetchColumn();

        if ($emailExists) {
            $_SESSION['error'] = 'This email address is already registered';
            header('Location: ../manage-users.php');
            exit();
        }

        // If email doesn't exist, proceed with insertion
        $stmt = $conn->prepare("INSERT INTO blog_users (first_name, last_name, email, password, role_id, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $password,
            $_POST['role_id'],
            $_POST['status']
        ]);

        $_SESSION['success'] = 'User added successfully!';
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Error adding user: ' . $e->getMessage();
    }
    
    header('Location: ../manage-users.php');
    exit();
}