<?php
session_start();

include('../connection.php');


// Columns to show up
$columnsToShow = ['name','userName', 'role', 'email', 'status'];

// name, fatherName, address, role, email, status

$searchTerm = isset($_GET['userName']) ? trim($_GET['userName']) : '';

function if_exists($result, $columnsToShow)
{   
    echo "username already taken";
}
if ($searchTerm) {
    // Search query
    $sql = "SELECT * FROM users WHERE userName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",  $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        if_exists($result, $columnsToShow);
    }
    
}

$conn->close();
?>


<script>
  // Function to handle delete confirmation and send AJAX request (optional)
  function deleteCertificate(certificateId) {
  if (confirm("Are you sure you want to delete this certificate?")) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if (this.responseText == "success") {
          alert("Certificate deleted successfully!");
          // Optionally, redirect back to search page after successful deletion
          window.location.href = "index.php";
        } else {
          alert("Error deleting certificate: " + this.responseText);
        }
      }
    };
    xhttp.open("POST", "delete_certificate.php", true); // Change URL to your delete script
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("certificateId=" + certificateId);
  }
}
</script>
