<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iron";

$con = mysqli_connect($servername, $username, $password, $dbname);


if (!$con) {
    die("Connection Failed");
}

// Consulta para sumar el campo amount de la tabla members
$sql_members = "SELECT SUM(amount) as total_amount_members FROM members";
$amountsum_members = mysqli_query($con, $sql_members) or die(mysqli_error($con));
$row_amountsum_members = mysqli_fetch_assoc($amountsum_members);
$total_amount_members = $row_amountsum_members['total_amount_members'];

// Consulta para sumar el campo amount de la tabla views
$sql_views = "SELECT SUM(amount) as total_amount_views FROM views";
$amountsum_views = mysqli_query($con, $sql_views) or die(mysqli_error($con));
$row_amountsum_views = mysqli_fetch_assoc($amountsum_views);
$total_amount_views = $row_amountsum_views['total_amount_views'];

// Sumar ambas cantidades
$total_amount = $total_amount_members + $total_amount_views;

echo $total_amount;
