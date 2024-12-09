<?php
session_start();
// Redirect if user is not logged in
if (!isset($_SESSION["id"])) {
    header("Location: http://localhost/RBAC/users");
    exit;
}

// Check session expiration
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $_SESSION['inactive'])) {
    session_destroy();
    header("Location: http://localhost/RBAC/users");
    exit;
}
// Include database connection
include('connection.php');

// Simulate a logged-in user (replace with session or actual login logic)
$userId = $_SESSION["id"]; // Example user ID

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();
$userName = $user['userName'];
$_SESSION["userName"]= $user['userName'];
$fullName = $user['name'];
$email = $user['email'];
$role = $user['role'];
$address = $user['address'];
// $avatar = $user['avatar'] ?: 'avatar-placeholder.png'; // Default if no avatar

// echo $userName;
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
    <script>
        // Function to check session status
        function checkSession() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "check_session.php", true);
            xhr.onload = function () {
                if (xhr.responseText === "expired") {
                    window.location.href = "http://localhost/RBAC/users/logout.php";
                }
            };
            xhr.send();
        }

        // Check session every 30 seconds
        setInterval(checkSession, 30000);
    </script>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <div class="avatar">
                <img src="avatar-placeholder.png" alt="User Avatar" id="userAvatar">
            </div>
            <h1 id="userName"><?php echo $fullName; ?></h1>
            <p id="userEmail"><?php echo $email; ?></p>
            <div class="header-buttons">
                <a href="logout.php" class="logout-button">Logout</a>
                <button class="privilege-button"
                    onclick="checkPrivilege(<?php if ($role == 'user') {
                        echo 0;
                    } else {
                        echo 1;
                    } ?>)">Privileged</button>
            </div>
        </div>
        <div class="profile-details">
            <h2>Profile Details</h2>
            <table>
                <tr>
                    <th>Full Name:</th>
                    <td id="fullName"><?php echo $fullName; ?></td>
                </tr>
                <tr>
                    <th>Username:</th>
                    <td id="username"><?php echo $userName; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td id="email"><?php echo $email; ?></td>
                </tr>
                <tr>
                    <th>role:</th>
                    <td id="role"><?php echo $role; ?></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td id="address"><?php echo $address; ?></td>
                </tr>
            </table>
        </div>
    </div>
</body>
<script>
    function checkPrivilege(isAuthorized) {
        if (isAuthorized) {
            window.location.href = 'privilege.php';
        } else {
            alert("Role Required: Admin/Viewer");
        }
    }
</script>

</html>