<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iron";

$con = mysqli_connect($servername, $username, $password, $dbname);

if(!$con){
    die("Connection Failed");
}

$sql = "SELECT * FROM members WHERE status ='Active'";
                $query = $con->query($sql);

                echo "$query->num_rows";
?>