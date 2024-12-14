<?php
session_start();
date_default_timezone_set("Asia/Kolkata");
if(isset($_SESSION['admin_reset'])){
    $id="admin";
}else{
    $id="users";
}
if (isset($_POST['submit'])) {
    $message='';
    $email = $_POST['email'];
    $_SESSION['emailid']=$email;
    include '../connection.php';
    $sql = "SELECT * FROM $id WHERE email= ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $to = $email;
        $subject = "OTP to reset your password";
        $otp = generateOTP($conn, $to);
        $conn->close();
        $msg = "Your OTP is " . $otp;
        $header = "From: RBAC Password Reset";
        $_SESSION['email'] = $email;
        $_SESSION['otp'] = $otp;
        // echo "now I'm running";
        if (
            @mail(
                $to,
                $subject,
                $msg,
                // implode("\r\n", ["MIME-Version: 1.0", "Content-type: text/html; charset=utf-8","From: bjcrazytechz@gmail.com"])
                $header
            )
        ) {
            header("location: verify.php");
            // echo "otp sent";
            // return true;
        } else {
            $message='Error! Could not send mail.';
            // return false;
        }



    } else {
        $message .='Invalid Email';
    }
}
function generateOTP($conn, $email)
{
    $str = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789";
    $strlen = strlen($str) - 1;
    $pass = "";
    for ($i = 0; $i < 6; $i++) { //Generating otp of length 6
        $pass .= $str[rand(0, $strlen)];
    }
    $sql2 = "SELECT * FROM `otp-lib` WHERE email= ? ";
    $stmt = $conn->prepare($sql2);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $time=date("Y-m-d H:i:s");
        $sql = "UPDATE `otp-lib` SET timestamp = ?, pass = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $hashed_password=password_hash($pass, PASSWORD_DEFAULT);
        $stmt->bind_param("sss",$time, $hashed_password, $email);
        $stmt->execute();
    } else {
        $sql = "INSERT INTO `otp-lib`(`email`, `pass`) VALUES (?,?)";
        $stmt = $conn->prepare($sql);
        $hashed_password=password_hash($pass, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();
    }


    return $pass;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>OTP Request</title>
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
                        <li><a href="../admin">Admin</a></li>
                        <li><a href="../users">User</a></li>
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
                        <?php if(isset($_SESSION['admin_reset'])){?>
                            <p font-weight="bold">A D M I N</p>
                            <?php
                            }else{?>
                                <p font-weight="bold">U S E R</p>
                                <?php
                            }?>
                        
                    </div>
                </div>
                <div class="col-md-6 right">
                    <div class="input-box sendotp">
                        <header>Enter Your Email</header>
                        <?php
                        if (isset($message)) {
                            echo $message;
                        }
                        ?>
                        <form method="POST" action="">
                            <div class="input-field">
                                <input type="email" class="input" id="email" name="email" required autocomplete="off">
                                <label for="email">Enter Email</label>
                            </div>
                            <div class="input-field">
                                <input type="submit" id="send-otp" name="submit" class="submit" value="Send OTP">
                            </div>
                        </form>
                    </div>

                    <!-- <div class="input-box" style="display:none;" id="otp-section">
                        <header>OTP Verification</header>
                        <form method="POST" action="otp-lib.php">
                            <div class="input-field">
                                <input type="text" class="input" id="otp" name="otp" required autocomplete="off">
                                <label for="otp">Enter OTP</label>
                            </div>
                            <div class="input-field">
                                <input type="submit" class="submit" value="Verify OTP">
                            </div>
                        </form>
                        <div class="countdown">
                            <span id="timer">10:00</span>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>