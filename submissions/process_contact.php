<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name    = trim($_POST['name'] ?? '');
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone   = trim($_POST['phone'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $subject && $message) {
        include '../pre/db_config.php'; // $conn is a PDO instance

        try {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, website, subject, message) VALUES (:name, :email, :phone, :website, :subject, :message)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':website', $website);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':message', $message);

            if ($stmt->execute()) {
                header("Location: ../index");
                exit;
            } else {
                echo "Error: Could not send your message. Please try again.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        echo "Please fill in all required fields and provide a valid email.";
    }
} else {
    echo "Invalid request.";
}