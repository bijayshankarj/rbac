<?php
session_start();
// Redirect if user is not logged in
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] != 1) {
  header("Location: http://localhost/rbac");
  exit;
}

// Check session expiration
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $_SESSION['inactive'])) {
  session_destroy();
  header("Location: http://localhost/rbac");
  exit;
}

include('connection.php');

$certificateId = isset($_POST['certificateId']) ? (int) $_POST['certificateId'] : 0;

if ($certificateId) {
  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $certificateId);

  if ($stmt->execute()) {
    echo "success"; // Send success message if deletion is successful
  } else {
    echo "Error deleting certificate: " . $conn->error; // Send error message on failure
  }
} else {
  echo "Invalid certificate ID."; // Handle invalid ID scenario
}

$conn->close();
?>