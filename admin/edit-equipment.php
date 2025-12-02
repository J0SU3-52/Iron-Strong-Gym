<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('location:../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Equipo de Gimnasio</title>
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
  <?php include 'includes/topheader.php' ?>
  <?php $page = 'update-equip';
  include 'includes/sidebar.php' ?>


  <div id="content">
    <div id="content-header">
      <div id="breadcrumb"> <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a> <a href="#" class="current">Lista de equipo</a> </div>
      <h1 class="text-center">Actualizar Detalles del Equipo <i class="fas fa-dumbbell"></i></h1>
    </div>
    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span12">

          <div class='widget-box'>
            <div class='widget-title'> <span class='icon'> <i class='fas fa-cogs'></i> </span>
              <h5>Tabla de Equipos</h5>
            </div>
            <div class='widget-content nopadding'>

              <?php

              include "dbcon.php";
              $qry = "select * from equipment";
              $cnt = 1;
              $result = mysqli_query($con, $qry);


              echo "<table class='table table-bordered table-hover'>
              <thead>
                <tr>
                  <th>#</th>
                  <th>E. Nombre</th>
                  <th>Descripcion</th>
                  <th>Cantidad</th>
                  <th>Marca</th>
                  <th>Fecha de ingreso</th>
                  <th>Accion</th>
                </tr>
              </thead>";

              while ($row = mysqli_fetch_array($result)) {

                echo "<tbody> 
               
                <td><div class='text-center'>" . $cnt . "</div></td>
                <td><div class='text-center'>" . $row['name'] . "</div></td>
                <td><div class='text-center'>" . $row['description'] . "</div></td>
                <td><div class='text-center'>" . $row['quantity'] . "</div></td>
                <td><div class='text-center'>" . $row['vendor'] . "</div></td>
                <td><div class='text-center'>" . $row['date'] . "</div></td>
                <td><div class='text-center'><a href='edit-equipmentform.php?id=" . $row['id'] . "'style='color:blue;'><i class='fas fa-edit'></i> Editar</a></div></td>
                
              </tbody>";
                $cnt++;
              }
              ?>

              </table>
            </div>
          </div>



        </div>
      </div>
    </div>
  </div>


  <div class="row-fluid">
    <div id="footer" class="span12"> <?php echo date("Y"); ?> &copy; Developed By Josué and Lazaro</a> </div>
  </div>

  <style>
    #footer {
      color: white;
    }
  </style>
  <script src="../js/jquery.min.js"></script>
  <script src="../js/matrix.js"></script>

</body>

</html>