<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "u432718683_analyticsai";
    // $dbname = "analyst_ai_mv";
} 
else if ($_SERVER['HTTP_HOST'] === 'analyticsai.in') {
    $servername = "localhost";
    $username = "u432718683_analyticsai";
    $password = "MetricVibes@2025";
    $dbname = "u432718683_analyticsai";
} else {
    die("Unknown domain. Database configuration not set.");
}
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>