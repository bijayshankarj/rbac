<?php
session_start();

if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > $_SESSION['inactive'])) {
    echo "expired";
    exit;
  }else if(!isset($_SESSION['start'])){
    echo "expired";
  }else{
    echo "active";
  }
?>
