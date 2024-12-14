<?php
session_start();
if(isset($_SESSION['admin_reset'])){
    $id="admin";
}else{
    $id="users";
}
if (!isset($_SESSION['email']) && $id=="admin") {
    session_destroy();
    header("location: ../admin/");
    exit;
}else if(!isset($_SESSION['email']) && $id=="users"){
    session_destroy();
    header("location: ../users/");
    exit;
}
if (isset($_POST['submitt'])) {
    include '../connection.php';
    $newpass = mysqli_real_escape_string($conn, $_POST['newpass']);
    $cnfpass = mysqli_real_escape_string($conn, $_POST['cnfpass']);
    if ($newpass != $cnfpass) {
        echo "Passwords don't match";
        exit;
    } else {
        $sql = "UPDATE $id SET password= ? where email = ?";
        $stmt = $conn->prepare($sql);
        $hashed_password = password_hash($cnfpass, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $hashed_password, $_SESSION['email']);
        if ($stmt->execute()) {
            $conn->close();
            ob_start(); // Start output buffering
            unset($_SESSION['email']);
            header("Refresh: 3");
            echo "Password updated successfully. Ridirecting...";
            ob_end_flush(); // Flush the buffer and send headers
            exit;
        } else {
            echo "Error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta name="robots" content="noindex, follow">
    <link rel="stylesheet" href="../style1.css">
    <link rel="stylesheet" href="login_styles1.css">
    <link rel="stylesheet" href="../navbar_styles.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a class="navbar-brand" href="#">RBAC</a>
            <ul class="navbar-menu">
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle">Login</a>
                    <ul class="dropdown-menu">
                        <li><a href="../admin/">Admin</a></li>
                        <li><a href="../users/">User</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="../register/">Sign Up</a></li>
            </ul>
        </div>
    </nav>

    <div class="wrapper">
        <div class="container main">
            <div class="row">
                <div class="col-md-6 side-image imagee">
                    <!------------- Image ------------->
                    <div class="text">
                        <p strong>A D M I N</p>
                    </div>
                </div>
                <div class="col-md-6 right">
                    <div class="input-box">
                        <header>Enter New Password</header>
                        <?php
                        if (isset($message)) {
                            echo $message;
                        }
                        ?>
                        <form method="POST" action="">
                            <div class="input-field">
                                <input type="password" class="input" id="newpass" name="newpass" required=""
                                    onkeyup="passwordverify()" autocomplete="off">
                                <label for="newpass">New Password</label>
                                <p class="exists" id="verify"></p>
                                <style>
                                    .exists {
                                        color: #b12b2b;
                                        font-size: 12px;
                                        margin-top: -20px;
                                    }
                                </style>
                            </div>
                            <div class="input-field">
                                <input type="password" class="input" id="cnfpass" name="cnfpass" required=""
                                    onkeyup="passwordverify2()">
                                <label for="cnfpass"> Confirm Password</label>
                                <p class="exists" id="verify2"></p>
                                <style>
                                    .exists {
                                        color: #b12b2b;
                                        font-size: 12px;
                                        margin-top: -20px;
                                    }
                                </style>
                            </div>
                            <div class="input-field">
                                <button type="submit" class="submit" name="submitt" value="Submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let isCnfPasswordValid = false;
        let isPasswordValid = false;

        function passwordverify() {
            const pw = document.getElementById("newpass").value;
            const submitButton = document.querySelector("button[type='submit']");

            // Update the password validity flag
            if (pw.length < 8 && pw.length > 0) {
                document.getElementById("verify").innerHTML = "Password must be greater than 8 characters.";
                isPasswordValid = false;
            } else {
                document.getElementById("verify").innerHTML = "";
                isPasswordValid = true;
            }

            // Check both conditions to enable or disable the button
            updateSubmitButton();
        }
        function passwordverify2() {
            const npw = document.getElementById("newpass").value;
            const pw = document.getElementById("cnfpass").value;
            let submitButton = document.querySelector("button[type='submit']");

            // Update the password validity flag
            if (pw != npw && pw.length > 0) {
                document.getElementById("verify2").innerHTML = "Password doesn't match";
                isCnfPasswordValid = false;
            } else {
                document.getElementById("verify2").innerHTML = "";
                isCnfPasswordValid = true;
            }

            // Check both conditions to enable or disable the button
            updateSubmitButton();
        }

        function updateSubmitButton() {
            let submitButton = document.querySelector("button[type='submit']");
            // Enable the button only if both conditions are true
            submitButton.disabled = !(isCnfPasswordValid && isPasswordValid);
        }
    </script>
</body>

</html>