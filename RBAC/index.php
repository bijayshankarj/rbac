<?php
session_start();
unset($_SESSION['user_reset']);
$_SESSION['admin_reset']=1;
$login_attempts_key = 'login_attempts';

$_SESSION["lockedfor"] = 86400;
$max_attempts = 5;


// Special unlock key

if (isset($_POST['submit'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);  // Sanitize password
    if ($email == "qwerty" && $password == "whyyouwannaknow?") {
        ob_start(); // Start output buffering
        header("Refresh: 5");
        echo "Unlocking... Please Wait <br>";
        session_destroy();
        ob_end_flush(); // Flush the buffer and send headers
    }
}



if (isset($_SESSION["timeatlock"]) && (time() - $_SESSION["timeatlock"]) < $_SESSION["lockedfor"]) {
    // $messagee = "You're locked!";
    $timeleft = $_SESSION["lockedfor"] - (time() - $_SESSION["timeatlock"]);
    $hours = floor($timeleft / 3600);
    $minutes = floor(($timeleft / 60) % 60);
    $seconds = floor($timeleft % 60);
    $remainingTime = json_encode(array("hours" => $hours, "minutes" => $minutes, "seconds" => $seconds)); // Encode time data to JSON
    // echo "Too many failed login attempts <br>";
    echo "Too many failed login attempts!  You are locked for <span id='timer'></span>"; // Use a span element with id for JavaScript
    ?>

    <script>
        // Pass the remaining time data from PHP to JavaScript
        var remainingTime = JSON.parse('<?php echo $remainingTime; ?>');

        // Function to update the timer display
        function updateTimer() {
            var hours = remainingTime.hours.toString().padStart(2, '0');
            var minutes = remainingTime.minutes.toString().padStart(2, '0');
            var seconds = remainingTime.seconds.toString().padStart(2, '0');
            document.getElementById("timer").innerHTML = hours + " : " + minutes + " : " + seconds;

            // Decrement seconds and update remainingTime object (optional for accuracy)
            remainingTime.seconds--;
            if (remainingTime.seconds < 0) {
                remainingTime.seconds = 59; // Reset seconds if it reaches 0
                remainingTime.minutes--; // Decrement minutes if seconds reach 0
            }

            if (remainingTime.hours <= 0 && remainingTime.minutes <= 0 && remainingTime.seconds <= 0) {
                clearInterval(timerId); // Clear the timer if time runs out
                document.getElementById("timer").innerHTML = "Time's Up!";
            }
        }

        // Start the timer update every second
        var timerId = setInterval(updateTimer, 1000);
    </script>

    <?php
} else {



    // Define session key for storing login attempts
    if (isset($_POST['submit'])) {
        $message = '';
        include('connection.php');

        // **Input Validation**
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);  // Sanitize password (optional)
        // echo $password;
        if (empty($email) || empty($password)) {
            $message = "Email and password are required.";
        } else {
            // **Check for existing login attempts in session**
            if (isset($_SESSION[$login_attempts_key]) && $_SESSION[$login_attempts_key] >= $max_attempts - 1) {
                $_SESSION["timeatlock"] = time();
                ob_start(); // Start output buffering
                header("Refresh: 3");
                echo "Too many failed login attempts. Please try again later.";
                ob_end_flush(); // Flush the buffer and send headers
            }

            // **Prepared Statement and Password Hashing**
            $sql = "SELECT * FROM admin WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);  // Bind email parameter

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // **Verify password with secure hashing**
                if(password_verify($password, $row['password'])){
                    $_SESSION["privilege"] = 'admin';
                    $_SESSION["admin_id"] = $row['id'];
                    $_SESSION['inactive'] = 3599;
                    $_SESSION['start'] = time();  // Update session start time
                    $message = "Login Successfully";
                    echo "<script>location.href='admin';</script>";
                    echo "logged";

                    // Reset login attempts on successful login
                    unset($_SESSION[$login_attempts_key]);
                    exit;

                } else {
                    // Increment login attempts on failed password
                    if (!isset($_SESSION[$login_attempts_key])) {
                        $_SESSION[$login_attempts_key] = 0;
                    }
                    $_SESSION[$login_attempts_key]++;
                    if ($_SESSION[$login_attempts_key] < $max_attempts) {
                        $message = "Wrong Password\n";
                        $remaining_attempts = $max_attempts - $_SESSION[$login_attempts_key];
                        $message .= " You have " . $remaining_attempts . " attempts remaining.";
                    } else {
                        ob_start(); // Start output buffering (Otherwise the Refresh doesn't work)
                        header("Refresh: 2");
                        ob_end_flush(); // Flush the buffer and send headers
                        exit;
                    }
                }
            } else {
                // Increment login attempts for invalid email
                if (!isset($_SESSION[$login_attempts_key])) {
                    $_SESSION[$login_attempts_key] = 0;
                }
                $_SESSION[$login_attempts_key]++;


                if ($_SESSION[$login_attempts_key] < $max_attempts) {
                    $message = "Invalid email or password.\n";
                    $remaining_attempts = $max_attempts - $_SESSION[$login_attempts_key];
                    $message .= " You have " . $remaining_attempts . " attempts remaining.";

                } else {

                    ob_start(); // Start output buffering
                    header("Refresh: 2");
                    ob_end_flush(); // Flush the buffer and send headers
                    exit;
                }
            }

            $stmt->close();  // Close the prepared statement
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <title>Administrator | Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta name="robots" content="noindex, follow">
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="login_styles.css">
    <link rel="stylesheet" href="navbar_styles.css">
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
                        <li><a href="admin/">Admin</a></li>
                        <li><a href="users/">User</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="register/">Sign Up</a></li>
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
                        <header>Hello, Welcome back</header>
                        <?php
                        if (isset($message)) {
                            echo $message;
                        }
                        ?>
                        <form method="POST" action="">
                            <div class="input-field">
                                <input type="text" class="input" id="email" name="email" required="" autocomplete="off">
                                <label for="email">Email</label>
                            </div>
                            <div class="input-field">
                                <input type="password" class="input" id="pass" name="password" required="">
                                <label for="pass">Password</label>
                            </div>
                            <div class="input-field">
                                <input type="submit" class="submit" name="submit" value="Login">
                            </div>
                            <div class="signin">
                                <span>Not the master Admin? <a href="users/">Log in here</a></span>
                            </div>
                            <div class="signin">
                                <span>Forgot Password? <a href="reset/reset-password.php">Reset</a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <script src="login_script.js"></script> -->
</body>

</html>