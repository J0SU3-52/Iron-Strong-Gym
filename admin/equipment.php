<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('location:../index.php');
  exit();
}

// Habilitar el informe de errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "dbcon.php";

// Comprobar la conexión a la base de datos
if (!$con) {
  die("Conexión fallida: " . mysqli_connect_error());
}

// Ejecutar la consulta
$qry = "SELECT * FROM equipment";
$result = mysqli_query($con, $qry);

// Comprobar si la consulta se ejecutó correctamente
if (!$result) {
  die("Error en la consulta: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <title>Equipo del gimnasio</title>
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

  <div id="header">
    <h1><a href="dashboard.html"></a></h1>
  </div>

  <?php include 'includes/topheader.php'; ?>
  <?php $page = 'list-equip';
  include 'includes/sidebar.php'; ?>

  <div id="content">
    <div id="content-header">
      <div id="breadcrumb">
        <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
        <a href="#" class="current">Lista de Equipos del Gimnasio</a>
      </div>
      <h1 class="text-center">Lista de Equipos del Gimnasio <i class="fas fa-dumbbell"></i></h1>
    </div>
    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span12">
          <div class='widget-box'>
            <div class='widget-title'>
              <span class='icon'><i class='fas fa-dumbbell'></i></span>
              <h5>Tabla de equipos</h5>
              <form id="custom-search-form" role="search" method="POST" action="search-equipment.php" class="form-search form-horizontal pull-right">
                <div class="input-append span12">
                  <input type="text" class="search-query" placeholder="Buscar" name="search" required>
                  <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                </div>
              </form>
            </div>
            <div class='widget-content nopadding'>
              <table class='table table-bordered table-hover'>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>E. Nombre</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Marca</th>
                    <th>Fecha de ingreso</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $cnt = 1;
                  while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td><div class='text-center'>" . $cnt . "</div></td>";
                    echo "<td><div class='text-center'>" . htmlspecialchars($row['name']) . "</div></td>";
                    echo "<td><div class='text-center'>" . htmlspecialchars($row['description']) . "</div></td>";
                    echo "<td><div class='text-center'>" . htmlspecialchars($row['quantity']) . "</div></td>";
                    echo "<td><div class='text-center'>" . htmlspecialchars($row['vendor']) . "</div></td>";
                    echo "<td><div class='text-center'>" . htmlspecialchars($row['date']) . "</div></td>";
                    echo "</tr>";
                    $cnt++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row-fluid">
    <div id="footer" class="span12"> <?php echo date("Y"); ?> &copy; Developed By Josué and Lazaro</div>
  </div>

  <style>
    #footer {
      color: white;
    }
  </style>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/matrix.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</body>

</html>