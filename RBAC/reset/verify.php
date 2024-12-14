<?php
session_start();
date_default_timezone_set("Asia/Kolkata");
include '../connection.php';
if (isset($_POST['submit'])) {
    if ($_SESSION['remainingTime'] > 0) {
        if (password_verify($_POST['otp'], $_SESSION['pass'])) {
            $sql = "DELETE FROM `otp-lib` WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['email']);
            $stmt->execute();
            header("location: newpass.php");
            exit;
        } else {
            echo "Invalid OTP";
        }
    } else {
        echo "OTP Expired";
        exit;
    }
    echo "submitted";

}

if (isset($_SESSION['otp']) && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM `otp-lib` WHERE email= ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $remainingTime = 600 - (time() - strtotime($row['timestamp']));
    $_SESSION['remainingTime'] = $remainingTime;
    $_SESSION['pass'] = $row['pass'];
} else {
    echo "Invalid OTP Request";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>OTP Verification</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta name="robots" content="noindex, follow">
    <link rel="stylesheet" href="../style1.css">
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
                    <div class="input-box" id="otp-section">
                        <header>OTP Verification</header>
                        <a style="font-size: 12px; text-align: center; display: block; margin-top: -30px; margin-bottom: -20px;">An OTP is send to <?php echo $_SESSION['emailid'];?></a>
                        <br>
                        <form method="POST" action="">
                            <div class="input-field">
                                <input type="text" class="input" id="otp" name="otp" required autocomplete="off">
                                <label for="otp">Enter OTP</label>
                            </div>
                            <div class="input-field">
                                <input type="submit" name="submit" class="submit" value="Verify OTP">
                            </div>
                        </form>
                        <div class="countdown">
                            <span style="margin-left: 10px;" id="timer"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Countdown Timer
        let timerElement = document.getElementById('timer');
        let countdownTime = <?php echo $remainingTime; ?>;
        const updateTimer = () => {
            let minutes = Math.floor(countdownTime / 60);
            let seconds = countdownTime % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            if (countdownTime > 0) {
                countdownTime--;
            } else {
                clearInterval(timerInterval);
                alert("Time expired. Please request a new OTP.");
                window.location.href = "reset-password.php";
            }
        };

        let timerInterval;
        timerInterval = setInterval(updateTimer, 1000);

    </script>
</body>

</html>