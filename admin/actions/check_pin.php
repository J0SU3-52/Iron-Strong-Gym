<?php
include 'dbcon.php';

if (isset($_POST['pin'])) {
  $pin = $_POST['pin'];
  $check_pin_query = "SELECT * FROM members WHERE pin = '$pin'";
  $check_pin_result = mysqli_query($conn, $check_pin_query);

  if (mysqli_num_rows($check_pin_result) > 0) {
    echo 'taken';
  } else {
    echo 'available';
  }
}
?>