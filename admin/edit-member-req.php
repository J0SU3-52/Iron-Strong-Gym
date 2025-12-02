<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
    exit; // Salir del script para evitar que se siga ejecutando
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Administrador del sistema de gimnasio</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="../css/fullcalendar.css" />
    <link rel="stylesheet" href="../css/matrix-style.css" />
    <link rel="stylesheet" href="../css/matrix-media.css" />
    <link rel="stylesheet" href="../css/jquery.gritter.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>

<body>

    <!--Header-part-->
    <div id="header">
        <h1><a href="dashboard.html"></a></h1>
    </div>

    <?php include 'includes/topheader.php' ?>

    <?php $page = 'members-update';
    include 'includes/sidebar.php' ?>

    <!--sidebar-menu-->
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb">
                <a href="index.html" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="#" class="tip-bottom">Administrar clientes</a>
                <a href="#" class="current">Actualizar clientes</a>
            </div>
            <h1>Actualizar detalles de clientes </h1>
        </div>
        <form role="form" action="index.php" method="POST">
            <?php

            // Verificar si se han enviado los datos del formulario
            if (isset($_POST['fullname'])) {

                // Verificar si el campo 'services' está definido en el formulario
                $services = isset($_POST["services"]) ? $_POST["services"] : '';

                // Resto del código
                $fullname = $_POST["fullname"];
                $dor = $_POST["dor"];
                $gender = $_POST["gender"];
                $gmail = $_POST["gmail"];
                $amount = $_POST["amount"];
                $plan = $_POST["plan"];
                $pin = $_POST["pin"];
                $current_pin = $_POST["current_pin"]; // Obtener el PIN actual
                $id = $_POST["id"];

                // calculamos bitch jejej
                //Hazlo bien skinny bitvh
                //$new_next_date = date('Y-m-d', strtotime("+$plan months", strtotime($dor)));

                // Comprobar si el PIN ha cambiado
                if ($pin !== $current_pin) {
                    $check_pin_query = "SELECT * FROM members WHERE pin = '$pin'";
                    $check_pin_result = mysqli_query($con, $check_pin_query);

                    if (mysqli_num_rows($check_pin_result) > 0) {
                        echo "<div id='pin-alert' class='alert alert-danger' role='alert'>PIN en uso intenta con otro</div>";
                        echo "<script>
                setTimeout(function() {
                    document.getElementById('pin-alert').style.display = 'none';
                    window.history.back();
                }, 2000);
            </script>";
                        exit; // Salir del script para evitar que se siga ejecutando
                    }
                }

                // Consulta de actualización
                $qry = "UPDATE members SET fullname='$fullname', dor='$dor', gender='$gender', gmail='$gmail', services='$services', amount='$amount', plan='$plan', pin='$pin'  WHERE user_id='$id'";
                $result = mysqli_query($con, $qry); // Ejecutar la consulta

                if (!$result) {
                    echo "<div class='container-fluid'>";
                    echo "<div class='row-fluid'>";
                    echo "<div class='span12'>";
                    echo "<div class='widget-box'>";
                    echo "<div class='widget-title'> <span class='icon'> <i class='fas fa-info'></i> </span>";
                    echo "<h5>Error Message</h5>";
                    echo "</div>";
                    echo "<div class='widget-content'>";
                    echo "<div class='error_ex'>";
                    echo "<h1 style='color:maroon;'>Error 404</h1>";
                    echo "<h3>Se produjo un error al actualizar sus datos.</h3>";
                    echo "<p>Inténtalo de nuevo</p>";
                    echo "<a class='btn btn-warning btn-big'  href='edit-member.php'>Regresar</a> </div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<div class='container-fluid'>";
                    echo "<div class='row-fluid'>";
                    echo "<div class='span12'>";
                    echo "<div class='widget-box'>";
                    echo "<div class='widget-title'> <span class='icon'> <i class='fas fa-info'></i> </span>";
                    echo "<h5>Message</h5>";
                    echo "</div>";
                    echo "<div class='widget-content'>";
                    echo "<div class='error_ex'>";
                    echo "<h1>Exito</h1>";
                    echo "<h3>¡Los detalles del cliente han sido actualizados!</h3>";
                    echo "<p>Los datos solicitados están actualizados. Por favor haga clic en el botón para regresar.</p>";
                    echo "<a class='btn btn-inverse btn-big'  href='clients-update.php'>Regresar</a> </div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<h3>USTED NO ESTÁ AUTORIZADO A REDIRIGIR ESTA PÁGINA. Volver a <a href='index.php'> PANEL </a></h3>";
            }
            ?>
        </form>
    </div>
    </div>
    </div>
    </div>

    <!--end-main-container-part-->

    <!--Footer-part-->
    <div class="row-fluid">
        <div id="footer" class="span12"> <?php echo date("Y"); ?> &copy; Developed By Josué and Lazaro</a> </div>
    </div>
</body>

</html>