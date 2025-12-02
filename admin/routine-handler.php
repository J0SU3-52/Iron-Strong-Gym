<?php
ob_start(); // Inicia el manejo de salida en búfer
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
    exit();
}

include 'actions/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $routine_name = $_POST['routine_name'];
    $title_routineday_Lunes = isset($_POST['title_routineday_Lunes']) ? $_POST['title_routineday_Lunes'] : '';
    $title_routineday_Tuesday = isset($_POST['title_routineday_Tuesday']) ? $_POST['title_routineday_Tuesday'] : '';
    $title_routineday_Wednesday = isset($_POST['title_routineday_Wednesday']) ? $_POST['title_routineday_Wednesday'] : '';
    $title_routineday_Thursday = isset($_POST['title_routineday_Thursday']) ? $_POST['title_routineday_Thursday'] : '';
    $title_routineday_Friday = isset($_POST['title_routineday_Friday']) ? $_POST['title_routineday_Friday'] : '';
    $title_routineday_Saturday = isset($_POST['title_routineday_Saturday']) ? $_POST['title_routineday_Saturday'] : '';
    //$contact = $_POST['contact'];
    $dor = $_POST['dor'];
    $gmail = $_POST['gmail'];
    $services = $_POST['services'];
    $ejerciciosSeleccionados = $_POST['ejerciciosSeleccionados'];

    if (isset($_POST['routine_id'])) {
        $routine_id = $_POST['routine_id'];
        // Consulta para actualizar los datos de la rutina
        $qry = "UPDATE routines SET fullname = '$fullname',
         routine_name = '$routine_name', 
         title_routineday_Lunes = '$title_routineday_Lunes', 
         title_routineday_Tuesday = '$title_routineday_Tuesday', 
         title_routineday_Wednesday = '$title_routineday_Wednesday',
         title_routineday_Thursday = '$title_routineday_Thursday',
         title_routineday_Friday = '$title_routineday_Friday',
         title_routineday_Saturday = '$title_routineday_Saturday',

           dor = '$dor',
            gmail = '$gmail', 
            services = '$services', 
            ejerciciosSeleccionados = '$ejerciciosSeleccionados' WHERE routine_id = '$routine_id'";
    } else {
        // Consulta para insertar una nueva rutina
        $qry = "INSERT INTO routines (
        fullname, 
        routine_name,
         title_routineday_Lunes,
          title_routineday_Tuesday, 
          title_routineday_Wednesday, 
          title_routineday_Thursday,
          title_routineday_Friday,
          title_routineday_Saturday,
          dor, 
          gmail, 
          services, 
          ejerciciosSeleccionados
          ) 
          VALUES 
          (
          '$fullname',
           '$routine_name', 
           '$title_routineday_Lunes', 
           '$title_routineday_Tuesday', 
           '$title_routineday_Wednesday', 
           '$title_routineday_Thursday',
           '$title_routineday_Friday',
           '$title_routineday_Saturday',
           '$dor', 
           '$gmail', 
           '$services', 
           '$ejerciciosSeleccionados')";
    }

    $result = mysqli_query($con, $qry);

    if (!$result) {
        echo "Error: " . mysqli_error($con);
        $message = "<div class='container-fluid'>";
        $message .= "<div class='row-fluid'>";
        $message .= "<div class='span12'>";
        $message .= "<div class='widget-box'>";
        $message .= "<div class='widget-title'> <span class='icon'> <i class='fas fa-info'></i> </span>";
        $message .= "<h5>Error Message</h5>";
        $message .= "</div>";
        $message .= "<div class='widget-content'>";
        $message .= "<div class='error_ex'>";
        $message .= "<h1 style='color:maroon;'>Error 404</h1>";
        $message .= "<h3>Ocurrió un error al guardar sus datos</h3>";
        $message .= "<p>Inténtalo de nuevo</p>";
        $message .= "<a class='btn btn-warning btn-big' href='create-routines.php'>Regresar</a> </div>";
        $message .= "</div>";
        $message .= "</div>";
        $message .= "</div>";
        $message .= "</div>";
        $message .= "</div>";
    } else {
        $message = "<div class='container-fluid'>";
        $message .= "<div class='row-fluid'>";
        $message .= "<div class='span12'>";
        $message .= "<div class='widget-box'>";
        $message .= "<div class='widget-title'> <span class='icon'> <i class='fas fa-info'></i> </span>";
        $message .= "<h5>Message</h5>";
        $message .= "</div>";
        $message .= "<div class='widget-content'>";
        $message .= "<div class='error_ex'>";
        $message .= "<h1>Éxito</h1>";
        $message .= "<h3>¡Se ha guardado la rutina!</h3>";
        $message .= "<p>Los datos han sido guardados correctamente. Por favor haga clic en el botón para regresar.</p>";
        $message .= "<a class='btn btn-inverse btn-big' href='routine.php'>Regresar</a> </div>";
        $message .= "</div>";
        $message .= "</div>";
        $message .= "</div>";
        $message .= "</div>";
        $message .= "</div>";
    }
} else {
    $message = "<h3>USTED NO ESTÁ AUTORIZADO A REDIRIGIR ESTA PÁGINA. Volver a <a href='index.php'> PANEL </a></h3>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Rutinas</title>
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
    <?php include 'includes/topheader.php'; ?>
    <?php $page = 'create-routines';
    include 'includes/sidebar.php'; ?>

    <!--sidebar-menu-->
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb">
                <a href="index.html" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="#" class="tip-bottom">Crear rutina</a>
                <a href="#" class="current">Rutina actualizada</a>
            </div>
            <h1>Rutinas</h1>
        </div>

        <div class="container">
            <?php echo $message; ?>
        </div>
    </div>
    <!--end-main-container-part-->
    <!--Footer-part-->
    <div class="row-fluid">
        <div id="footer" class="span12"> <?php echo date("Y"); ?> &copy; Developed By Josué and Lazaro</a> </div>
    </div>
    <style>
        #footer {
            color: white;
        }
    </style>
    <!--end-Footer-part-->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/matrix.js"></script>
</body>

</html>

<?php
ob_end_flush(); // Envía el búfer de salida y desactiva el manejo de salida en búfer
?>