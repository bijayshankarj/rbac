<?php
session_start();
include('connection.php');
if (isset($_SESSION['admin_id'])) {
    $conn->close();
    session_destroy();
    header("Location: http://localhost/rbac");
    exit;
}
$id = $_SESSION['id'];
$conn->query("UPDATE users SET status = 'Inactive' WHERE id = $id");
$conn->close();
session_destroy();
header("Location: http://localhost/rbac/users");
exit;
?>