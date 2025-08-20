<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if ($email) {
        include '../pre/db_config.php'; // $conn is a PDO instance

        try {
            $stmt = $conn->prepare("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (:email)");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: ../index");
                exit;
            } else {
                echo "Error: Could not subscribe. Please try again.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        echo "Invalid email address.";
    }
} else {
    echo "No email submitted.";
}