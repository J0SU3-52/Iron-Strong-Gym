<?php

session_start();
//the isset function to check username is already logged in and stored on the session
if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Siempre que recibas un id desde $_GET, es buena práctica validarlo como entero para evitar inyecciones SQL:


    include 'dbcon.php';

    // Eliminar dependencias en la tabla attendance
    $qry = "DELETE FROM attendance WHERE member_id=$id";
    mysqli_query($con, $qry);

    // Luego eliminar el miembro en la tabla members
    $qry = "DELETE FROM members WHERE user_id=$id";
    $result = mysqli_query($con, $qry);

    if ($result) {
        echo "BORRADO";
        header('Location:../clients-expired.php');
    } else {
        echo "ERROR!!";
    }
}
