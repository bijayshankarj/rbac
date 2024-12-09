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


$certificateId = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Get certificate ID from URL parameter

// Check if a valid ID is provided
if (!$certificateId) {
    echo "Invalid user ID.";
    exit;
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $certificateId);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Prepare form with existing data
    $name = $row['name'];
    $fatherName = $row['fatherName'];
    $address = $row['address'];
    $email = $row['email'];
    $role = $row['role'];
    $userName = $row['userName'];


    // Handle form submission (if submitted)
    if (isset($_POST['submit'])) {
        $name = trim($_POST['name']);
        $fatherName = trim($_POST['fatherName']);


        $address = trim($_POST['address']);
        $email = trim($_POST['email']);
        $role = trim($_POST['role']);
        $userName = trim($_POST['userName']);


        //prepared statement sql 
        $stmt = $conn->prepare("UPDATE users SET name = ?, fatherName = ?, address = ?, email = ?, role = ?, userName = ? WHERE id = ?");

        // Bind parameters
        $stmt->bind_param("ssssssi", $name, $fatherName, $address, $email, $role, $userName, $certificateId);

        // Execute the statement
        if ($stmt->execute()) {
            // Optionally, redirect back to search page
            //   header("Location: search.html");
            $message = '<div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> Data updated successfully.
                    </div>';
        } else {
            echo "Error updating certificate: " . $conn->error;
            $message = '<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Error!</strong> Failed to add data.
                    </div>';
        }
    }

    $conn->close();
    ?>
    <!-- ....................................................Display Content......................................................-->
    <!DOCTYPE html>
    <html lang="en">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

    <head>
        <title>Admin | Edit User</title>
        <script>
            // Function to check session status
            function checkSession() {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "check_session.php", true);
                xhr.onload = function () {
                    if (xhr.responseText === "expired") {
                        window.location.href = "http://localhost/login%20page/login/logout.php";
                    }
                };
                xhr.send();
            }

            // Check session every 30 seconds
            setInterval(checkSession, 30000);
        </script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, follow">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

        <style>
            body {
                /* background: linear-gradient(to right, #4095e4, #2575fc); */
                background: url(../images/rm222-mind-16.jpg);
                background-size: 100%;
                font-family: 'Arial', sans-serif;
            }

            @media only screen and (max-width: 768px) {
                body {
                    /* z-index: -1; */
                    /* position: absolute; */
                    background: url(images/rm222-mind-16.jpg);
                    background-size: 350%;
                    /* transform: rotate(90deg); */
                }
            }

            .container-login100 {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 15px;
            }

            .wrap-login100 {
                width: 100%;
                max-width: 800px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                padding: 30px;
            }

            .login100-form-title {
                font-size: 30px;
                color: #333;
                font-weight: 700;
                text-align: center;
                margin-bottom: 30px;
                position: relative;
            }

            .login100-form-title::after {
                content: '';
                width: 0px;
                height: 3px;
                background: #4095e4;
                display: block;
                margin: 0 auto;
                margin-top: 10px;
            }

            .wrap-input100 {
                position: relative;
                border-bottom: 2px solid #d9d9d9;
                margin-bottom: 30px;
            }

            .input100 {
                font-size: 16px;
                color: #555;
                line-height: 1.2;
                display: block;
                width: 100%;
                background: transparent;
                padding: 10px 0;
                border: none;
                border-radius: 0;
                border-bottom: 2px solid transparent;
                transition: border-color 0.3s;
            }

            .input100:focus {
                outline: none;
                border-bottom-color: #4095e4;
            }

            .symbol-input100 {
                font-size: 18px;
                color: #999;
                position: absolute;
                bottom: 10px;
                right: 0;
            }

            .custom-file-label::after {
                content: "Browse";
                background: #4095e4;
                color: white;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
            }

            .login100-form-btn {
                font-size: 16px;
                color: white;
                line-height: 1.2;
                text-transform: uppercase;
                background: #4095e4;
                border: none;
                border-radius: 5px;
                padding: 10px 25px;
                cursor: pointer;
                /* transition: background 0.3s; */

            }

            .login100-form-btn:hover {
                background: #2575fc;
            }

            a {
                color: #4095e4;
                text-decoration: none;
                margin-top: 20px;
                display: block;
                text-align: left;
                width: max-content;
            }

            .container-login100-form-btn {
                display: flex;
                justify-content: center;
            }
        </style>

    </head>

    <body>
        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100 p-l-20 p-r-20 p-t-20 p-b-30" style="width:800px;">
                    <form class="login100-form validate-form" method="POST" action="" enctype="multipart/form-data">
                        <span class="login100-form-title p-b-55">
                            Edit User Details
                        </span>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if (isset($message)) {
                                    echo $message;
                                }
                                //   echo "<label for='name'>Name:</label>";
                                //   echo "<input type='text' name='name' id='name' value='$name' required>";
                                //   echo "<br>";
                                ?>
                            </div>
                            <div class="col-md-6">
                                <div class="wrap-input100 validate-input m-b-16" data-validate="Name is required">
                                    <input class="input100" type='text' name='name' id='name' value="<?php echo $name; ?>"
                                        required>
                                    <!-- <input class="input100" type="text" name="name" placeholder="Name"> -->
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <span class="lnr lnr-envelope"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="wrap-input100 validate-input m-b-16" data-validate="father's name is required">
                                    <input class="input100" type='text' name='fatherName' id='fatherName'
                                        value="<?php echo $fatherName; ?>" required>
                                    <!-- <input class="input100" type="text" name="fatherName" placeholder="father's name"> -->
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <span class="lnr lnr-lock"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="wrap-input100 validate-input m-b-16" data-validate="Address is required">
                                    <input class="input100" type='text' name='address' id='address'
                                        value="<?php echo $address; ?>" required>
                                    <!-- <input class="input100" type="text" name="address" placeholder="address"> -->
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <span class="lnr lnr-envelope"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wrap-input100 validate-input m-b-16" data-validate="Email is required">
                                    <input class="input100" type='text' name='email' id='email'
                                        value="<?php echo $email; ?>" required>
                                    <!-- <input class="input100" type="text" name="email" placeholder="Course Name"> -->
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <span class="lnr lnr-lock"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="wrap-input100 validate-input m-b-16" data-validate=" userName is required">
                                    <input class="input100" type='text' name='userName' id='userName'
                                        value="<?php echo $userName; ?>" required>
                                    <!-- <input class="input100" type="text" name="userName" placeholder="Certificate Code"> -->
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <span class="lnr lnr-envelope"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="wrap-input100 validate-input m-b-16" data-validate="Role is required">
                                    <!-- <input class="input100" type='select' name='role' id='role' value="<?php echo $role; ?>" required> -->
                                    <select class="input100" name='role' id='role' value="<?php echo $role; ?>">
                                        <?php
                                        if ($role == "user") {
                                            ?>
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                            <option value="viewer">Viewer</option>
                                            <?php
                                        } else if ($role == "admin") {
                                            ?>
                                                <option value="admin">Admin</option>
                                                <option value="user">User</option>
                                                <option value="viewer">Viewer</option>
                                            <?php
                                        } else {
                                            ?>
                                                <option value="viewer">Viewer</option>
                                                <option value="admin">Admin</option>
                                                <option value="user">User</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <!-- <input class="input100" type="text" name="role" placeholder="Faculty Name"> -->
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <span class="lnr lnr-envelope"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="wrap-input100 validate-input m-b-16" data-validate="Valid email is required">
                                    <div class="custom-file mb-3">
                                        <!-- <input type="file" class="custom-file-input" id="customFile"
                                            name="upload_certificate">
                                        <label class="custom-file-label" for="customFile">Upload Certificate(Pdf
                                            only)</label> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container-login100-form-btn p-t-25">
                            <button type="submit" name="submit" class="login100-form-btn">
                                Update
                            </button>


                        </div>
                        <div>
                            <span><a href="logout.php">Log out</a></span>
                            <span><a href='privilege.php'>Back to Search</a></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

    </html>






    <!-- .....................................................................................................................-->
    <?php
} else {
    echo "Certificate not found.";
    $conn->close();
}


?>

<script>
    // Function to handle delete confirmation and send AJAX request (optional)
    function deleteCertificate(certificateId) {
        if (confirm("Are you sure you want to delete this certificate?")) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText == "success") {
                        alert("Certificate deleted successfully!");
                        // Optionally, redirect back to search page after successful deletion
                        window.location.href = "search.php";
                    } else {
                        alert("Error deleting certificate: " + this.responseText);
                    }
                }
            };
            xhttp.open("POST", "delete_certificate.php", true); // URL to delete script
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("certificateId=" + certificateId);
        }
    }
</script>