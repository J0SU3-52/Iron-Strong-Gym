<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
if(!isset($_SESSION['user_id'])){
    header('location:../index.php');
}

// Habilitar visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['id'])){
    $id = $_GET['id'];

    include 'dbcon.php';

    $qry = "UPDATE members SET notify = '1' WHERE user_id = $id";
    $result = mysqli_query($con, $qry);

    if($result){
        // Código para enviar el correo electrónico
        require '../vendor/phpmailer/phpmailer/src/Exception.php';
        require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require '../vendor/phpmailer/phpmailer/src/SMTP.php';

        $query = "SELECT gmail, fullname FROM members WHERE user_id = $id";
        $result = mysqli_query($con, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $gmail = $row['gmail'];
            $fullname = $row['fullname'];
        } else {
            die('Error al obtener los datos del cliente: ' . mysqli_error($con));
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 2;
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ironstrongimnasio@gmail.com';
            $mail->Password = 'x h i f f a q z l p g x q r n q';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('ironstrongimnasio@gmail.com', 'Arturo Osorio Ramirez');
            $mail->addAddress($gmail, $fullname);

            $mail->isHTML(true);

            $mail->Subject = 'PAGO DE MENSUALIDAD';
            $mail->Body    = 'Hola ' . $fullname . ', este es un aviso que tu mensualidad ha vencido';

            $mail->send();
            echo 'Mensaje enviado exitosamente';
        } catch (Exception $e) {
            echo 'El mensaje no pudo ser enviado. Error del correo: ', $mail->ErrorInfo;
        }

        echo '<script>window.location.href = "payment.php";</script>';
    } else {
        echo 'ERROR!!';
    }
}
?>
