<?php
$to=$_POST['email'];
$subject="OTP to reset your password";
$msg="Your OTP is ";
$header="From: RBAC BJ";
// echo "now I'm running";
if (@mail($to, 
$subject, 
$msg, 
// implode("\r\n", ["MIME-Version: 1.0", "Content-type: text/html; charset=utf-8","From: bjcrazytechz@gmail.com"])
$header
)
) {
    echo "otp sent";
    // return true;
} else {
    echo "error";
    // return false;
}

?>