<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION["admin_id"])) {
  header("Location: http://localhost/RBAC");
  // echo "<script>window.location.href = 'index.php';</script>";
  exit;
}

// Check session expiration
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $_SESSION['inactive'])) {
  session_destroy();
  header("Location: http://localhost/RBAC");
  exit;
}

include('connection.php');


// Columns to show up
$columnsToShow = ['name', 'userName', 'role', 'email', 'status'];

// name, fatherName, address, role, email, status

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Initial query to fetch all data
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Function to build the table HTML
// function buildTable($result) {
//     if ($result->num_rows > 0) {
//         echo "<table>";
//         echo "<tr>";
//         // Display table headers based on your column names
//         foreach ($result->fetch_fields() as $field) {
//             echo "<th>" . ucfirst($field->name) . "</th>";
//         }
//         echo "<th>Actions</th>";
//         echo "</tr>";

//         echo "<tbody>";
//         while ($row = $result->fetch_assoc()) {
//             echo "<tr>";
//             foreach ($row as $value) {
//                 echo "<td>" . $value . "</td>";
//             }
//             $certificateId = $row['id']; // Replace 'id' with your primary key field name
//             echo "<td>";
//             echo "<a href='edit.php?id=$certificateId'>Edit</a> ";
//             echo "<button type='button' onclick='deleteCertificate($certificateId)'>Delete</button>";
//             echo "</td>";
//             echo "</tr>";
//         }
//         echo "</tbody>";
//         echo "</table>";
//     } else {
//         echo "No records found.";
//     }
// }

function buildTable($result, $columnsToShow)
{
  // echo 'hi';
  if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>";
    // Display table headers for specified columns
    foreach ($result->fetch_fields() as $field) {
      if (in_array($field->name, $columnsToShow)) {
        echo "<th>" . ucfirst($field->name) . "</th>";
      }
    }
    echo "<th>Actions</th>"; // Always show the actions column
    echo "</tr>";

    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      foreach ($columnsToShow as $column) {

        echo "<td>" . $row[$column] . "</td>";
      }
      // Actions column remains the same
      $certificateId = $row['id'];
      echo "<td>";
      echo "<button onclick=\"window.location.href='edit.php?id=$certificateId'\">Edit</button>";
      echo "<button class='action-button' type='button' onclick='deleteCertificate($certificateId)'>Delete</button>";

      echo "</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "<style>.action-button {margin-left: 10px; margin-right:-25px;}</style>";
  } else {
    echo "No records found.";
  }
}
if ($searchTerm) {
  // Search query
  $sql = "SELECT * FROM users WHERE ";
  $searchFields = array("name", "userName", "status", "email", "role");
  $firstField = true;
  foreach ($searchFields as $field) {
    if (!$firstField) {
      $sql .= " OR ";
    }
    $sql .= $field . " LIKE '%$searchTerm%'";
    $firstField = false;
  }
  // echo $sql;
  $result = $conn->query($sql);

  // Display search results
  buildTable($result, $columnsToShow);
} else {

  // Build the table if nothing is searched
  buildTable($result, $columnsToShow);
}

$conn->close();
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