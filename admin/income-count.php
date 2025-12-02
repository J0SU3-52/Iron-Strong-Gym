<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iron";

$con = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    die("Connection Failed");
}

$sql = "SELECT SUM( amount) FROM members";
        $amountsum = mysqli_query($conn, $sql) or die(mysqli_error($sqli));
        $row_amountsum = mysqli_fetch_assoc($amountsum);
        $totalRows_amountsum = mysqli_num_rows($amountsum);
        echo $row_amountsum['SUM( amount)'];
?>