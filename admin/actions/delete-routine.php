<?php

session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    include 'dbcon.php';

    // Use the correct column name for the routine ID
    $qry = "DELETE FROM routines WHERE routine_id=$id";
    $result = mysqli_query($con, $qry);

    if ($result) {
        echo "BORRADO";
        header('Location:../eliminate-routine.php');
    } else {
        echo "ERROR!!";
    }
} else {
    echo "ID no proporcionado.";
}
