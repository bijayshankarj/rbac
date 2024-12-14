<?php
session_start();
include('../connection.php');

$message = '';

if (isset($_POST['submit'])) {
    // echo "clickde";
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $fatherName = mysqli_real_escape_string($conn, $_POST['fatherName']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if user already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> Email already exists.
        </div>';
    } else {
        $target_dir = "../upload/";
        $uploadOk = 1;

        $filename = uniqid() . "-" . time();
        $extension = pathinfo($_FILES["profilePic"]["name"], PATHINFO_EXTENSION);
       
        $basename = $filename . "." . $extension;
        $target_file = $target_dir . $basename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        echo $imageFileType;

        // Check file type
        if ($imageFileType != "jpg" && $imageFileType != "png") {
            $message = '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Error!</strong> Sorry, only jpg/png files are allowed.
            </div>';
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $sql = "INSERT INTO users (name, fatherName, address, email, userName, password)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $fatherName, $address, $email, $userName, $hashed_password);
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Success!</strong> Data added successfully without file.
            </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Error!</strong> Failed to add data.
            </div>';
        }

        } else {
            // Upload file
            if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
                // Insert data into database
                $sql = "INSERT INTO users (name, fatherName, address, email, userName, password, profilePic)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $name, $fatherName, $address, $email, $userName, $hashed_password, $target_file);
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Success!</strong> Data added successfully.
            </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Error!</strong> Failed to add data.
            </div>';
        }
            } else {
                $message = '<div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error!</strong> Sorry, there was an error uploading your file.
                </div>';
            }
        }
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <title>User | Registration</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    <style>
        body {
            background: url(images/rm222-mind-16.jpg);
            background-size: 100%;
            font-family: 'Arial', sans-serif;
        }

        @media only screen and (max-width: 768px) {
            body {
                background: url(images/rm222-mind-16.jpg);
                background-size: 350%;
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
                        User Registration
                    </span>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (isset($message)) {
                                echo $message;
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <div class="wrap-input100 validate-input m-b-16" data-validate="Name is required">
                                <input class="input100" type="text" name="name" placeholder="Full Name" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <span class="lnr lnr-envelope"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="wrap-input100 validate-input m-b-16" data-validate="Father's Name is required">
                                <input class="input100" type="text" name="fatherName" placeholder="Father's Name"
                                    required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <span class="lnr lnr-lock"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="wrap-input100 validate-input m-b-16" data-validate="Address is required">
                                <input class="input100" type="text" name="address" placeholder="Address" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <span class="lnr lnr-envelope"></span>
                                </span>
                            </div>
                        </div>


                        <div class="col-md-6">


                            <div class="wrap-input100 validate-input m-b-16" data-validate=" UserName is required">
                                <input class="input100" type="text" id="userName" name="userName" onkeyup="searchData()"
                                    placeholder="Choose a UserName" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <span class="lnr lnr-envelope"></span>
                                </span>
                            </div>
                            <p class="exists" id="results"></p>
                            <style>
                                .exists {
                                    color: #b12b2b;
                                    font-size: 12px;
                                    margin-top: -25px;
                                }
                            </style>
                        </div>
                        <div class="col-md-6">
                            <div class="wrap-input100 validate-input m-b-16" data-validate="Valid Email is required">
                                <input class="input100" type="text" name="email" placeholder="Email" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <span class="lnr lnr-envelope"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="wrap-input100 validate-input m-b-16"
                                data-validate=" Certificate Status is required">
                                <input class="input100" id="password" type="password" name="password"
                                    placeholder="Password" onkeyup="passwordverify()" required>
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <span class="lnr lnr-envelope"></span>
                                </span>
                            </div>
                            <p class="exists" id="verify"></p>
                        </div>

                        <div class="col-md-12">
                            <div class="wrap-input100 validate-input m-b-16" data-validate="Valid file is required">
                                <div class="custom-file mb-3">
                                    <input type="file" class="custom-file-input" id="customFile"
                                        name="profilePic">
                                    <label class="custom-file-label" for="customFile">Profile Picture ( jpg / png
                                        only)</label>
                                </div>
                            </div>
                            
                        </div>

                        
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="container-login100-form-btn p-t-25">
                        <button type="submit" name="submit" class="login100-form-btn">
                            Register
                        </button>
                    </div>
                    <div>
                        <span>Already have an account?<a href="../users">Login</a></span>
                        <span><a href='../'>Home</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        let isUsernameValid = true;
        let isPasswordValid = true;

        function searchData() {
            const searchTerm = document.getElementById("userName").value;
            const submitButton = document.querySelector("button[type='submit']");
            const xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the results display
                    document.getElementById("results").innerHTML = this.responseText;

                    // Update the username validity flag
                    if (this.responseText.includes("username already taken")) {
                        isUsernameValid = false;
                    } else {
                        isUsernameValid = true;
                    }

                    // Check both conditions to enable or disable the button
                    updateSubmitButton();
                }
            };

            xhttp.open("GET", "search.php?userName=" + encodeURIComponent(searchTerm), true);
            xhttp.send();
        }

        function passwordverify() {
            const pw = document.getElementById("password").value;
            const submitButton = document.querySelector("button[type='submit']");

            // Update the password validity flag
            if (pw.length < 8) {
                document.getElementById("verify").innerHTML = "Password must be greater than 8 characters.";
                isPasswordValid = false;
            } else {
                document.getElementById("verify").innerHTML = "";
                isPasswordValid = true;
            }

            // Check both conditions to enable or disable the button
            updateSubmitButton();
        }

        function updateSubmitButton() {
            const submitButton = document.querySelector("button[type='submit']");
            // Enable the button only if both conditions are true
            submitButton.disabled = !(isUsernameValid && isPasswordValid);
        }


        window.onload = searchData;
    </script>
</body>

</html>