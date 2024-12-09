<?php
session_start();

// Mock credentials (replace with database query in production)
$adminUsername = "admin";
$adminPassword = "password123";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin/index.html"); // Redirect to the dashboard
        exit;
    } else {
        echo "<script>alert('Invalid credentials!');</script>";
        // Sleep(2);
        // header("Location: index.php"); // Redirect to the login page
        // exit;
        echo "<script>window.location.href = 'index.php'; </script>";
    }
}
?>
