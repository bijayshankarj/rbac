<?php
// $hash = isset($_GET['hash']) ? (int) $_GET['hash'] : 0;

// if(!isset($hash) || $hash!='wannahash'){
//   header("Location: http://localhost/login%20page/login/index.php");
//   exit;
// }
require('connection.php');

$sql = "SELECT email, password FROM admin";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $user_id = $row['email'];
    $old_password = $row['password'];
    $hashed_password = password_hash($old_password, PASSWORD_DEFAULT);

    $update_sql = "UPDATE admin SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $hashed_password, $user_id);
    $stmt->execute();
    $stmt->close();
  }
  echo "Passwords hashed successfully!";
} else {
  echo "No users found in the database.";
}

$conn->close();
?>