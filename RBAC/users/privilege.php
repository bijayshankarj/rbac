<?php
session_start();
// Include database connection
include('connection.php');

// Redirect if user is not logged in
if (!isset($_SESSION["admin_id"]) || $_SESSION["admin_id"] != 1) {
    if (!isset($_SESSION["id"])) {
        header("Location: http://localhost/RBAC/users");
        // echo "<script>window.location.href = 'index.php';</script>";
        exit;
    } else if ($_SESSION["privilege"] != 'admin' && $_SESSION["privilege"] != 'viewer') {
        echo '<script>alert("Role Required: Admin/Viewer");</script>';
        sleep(1);
        header("Location: http://localhost/RBAC/users/profile.php");
        exit;
    }
}

// Check session expiration
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $_SESSION['inactive'])) {
    session_destroy();
    header("Location: http://localhost/RBAC/users");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RBAC Application</title>
    <link rel="stylesheet" href="priv_styles.css">
</head>

<body>
    <div id="app">
        <h1>RBAC Admin Dashboard</h1>
        <div class="header-buttons">
            <a href="logout.php" class="logout-button">Logout</a>
            <?php if(isset($_SESSION["userName"])){ echo '<a href="../" class="logout-button">Go Back</a>';} ?>
        </div>
        <section id="user-management">
            <h2>User Management</h2>
            <form action="" method="get">
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" onkeyup="searchData()">
            </form>
            <div id="results"></div>
        </section>

        <script>
            function deleteCertificate(certificateId) {
                if (confirm("Are you sure you want to delete this certificate?")) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            if (this.responseText == "success") {
                                alert("User deleted successfully!");
                                window.location.href = "privilege.php";
                            } else {
                                alert("Error deleting certificate: " + this.responseText);
                            }
                        }
                    };
                    xhttp.open("POST", "delete_certificate.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("certificateId=" + certificateId);
                }
            }

            function searchData() {
                var searchTerm = document.getElementById("search").value;
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("results").innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "search.php?search=" + searchTerm, true);
                xhttp.send();
            }
            window.onload = searchData;
        </script>
    </div>

</body>

</html>