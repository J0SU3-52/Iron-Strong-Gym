<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iron";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
}

$sql = "SELECT SUM(amount) AS total_amount FROM equipment";
$amountsum = mysqli_query($con, $sql);

if (!$amountsum) {
    die("Query Failed: " . mysqli_error($con));
}

$row_amountsum = mysqli_fetch_assoc($amountsum);

if ($row_amountsum && isset($row_amountsum['total_amount'])) {
    echo $row_amountsum['total_amount'];
} else {
    echo "No data found";
}

mysqli_close($con);
?>
