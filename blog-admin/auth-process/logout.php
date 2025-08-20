<?php
session_start();

// Store the message in a temporary variable
$message = 'You have been successfully logged out';

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Start a new session for the message
session_start();
$_SESSION['success'] = $message;

// Redirect to login page
header('Location: ../');  // Add .php extension for consistency
exit();