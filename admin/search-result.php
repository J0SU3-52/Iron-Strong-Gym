<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('location:../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Administrador del sistema de gimnasio</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
  <link rel="stylesheet" href="../css/matrix-style.css" />
  <link rel="stylesheet" href="../css/matrix-media.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>

<body>

  <!--Header-part-->
  <div id="header">
    <h1><a href=""></a></h1>
  </div>
  <!--close-Header-part-->


  <!--top-Header-menu-->
  <?php include 'includes/topheader.php' ?>


  <!--sidebar-menu-->
  <?php $page = 'payment';
  include 'includes/sidebar.php' ?>
  <!--sidebar-menu-->

  <div id="content">
    <div id="content-header">
      <div id="breadcrumb">
        <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
        <a href="payment.php">Pagos</a>
        <a href="#" class="current">Resultados Busqueda</a>
      </div>
      <h1 class="text-center">Pago de cliente registrado <i class="fas fa-group"></i></h1>
    </div>
    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span12">

          <div class='widget-box'>
            <div class='widget-title'> <span class='icon'> <i class='fas fa-th'></i> </span>
              <h5>Tabla de pagos de cliente s</h5>
              <form id="custom-search-form" role="search" method="POST" action="search-result.php" class="form-search form-horizontal pull-right">
                <div class="input-append span12">
                  <input type="text" class="search-query" placeholder="Buscar" name="search" required>
                  <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                </div>
              </form>
            </div>

            <div class='widget-content nopadding'>

              <?php

              include "dbcon.php";
              $search = $_POST['search'];
              $qry = "select * from members where fullname like '%$search%' or fullname or pin like '%$search%'";
              $cnt = 1;
              $result = mysqli_query($con, $qry);

              if (mysqli_num_rows($result) == 0) {

                echo "<div class='error_ex'>
            <h1>403</h1>
            <h3>Opps, Cliente no Registrado!!</h3>
            <p>Parece que no existe tal registro disponible en nuestra base de datos.</p>
            <a class='btn btn-danger btn-big'  href='payment.php'>Regresar</a> </div>'";
              } else {

                echo "<table class='table table-bordered table-hover'>
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre completo</th>
            <th>Fecha de inicio</th>
            <th>Fecha de Pago</th>
            <th>Próxima Fecha de Pago</th>
            <th>Cantidad</th>
            <th>Servicio elegido</th>
            <th>Plan</th>
            <th>Accion</th>
            <th>Recordar Pago</th>
          </tr>
        </thead>";


                while ($row = mysqli_fetch_array($result)) {



                  echo "<tbody> 
               
                <td><div class='text-center'>" . $cnt . "</div></td>
                <td><div class='text-center'>" . $row['fullname'] . "</div></td>
                <td><div class='text-center'>" . $row['dor'] . "</div></td>
                <td><div class='text-center'>" . $row['paid_date'] . "</div></td>
                <td><div class='text-center'>" . $row['next_date'] . "</div></td>
                <td><div class='text-center'>$" . $row['amount'] . "</div></td>
                <td><div class='text-center'>" . $row['services'] . "</div></td>
                <td><div class='text-center'>" . $row['plan'] . " Mes/es</div></td>
                <td><div class='text-center'><a href='user-payment.php?id=" . $row['user_id'] . "'><button class='btn btn-success btn'><i class='fas fa-dollar-sign'></i> Hacer el pago</button></a></div></td>
                <td><div class='text-center'><a href='sendReminder.php?id=" . $row['user_id'] . "'><button class='btn btn-danger btn'>Notificar prox. pago</button></a></div></td>
              </tbody>";
                  $cnt++;
                }
              }
              ?>

              </table>
            </div>
          </div>



        </div>
      </div>
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

  <style>
    #custom-search-form {
      margin: 0;
      margin-top: 5px;
      padding: 0;
    }

    #custom-search-form .search-query {
      padding-right: 3px;
      padding-right: 4px \9;
      padding-left: 3px;
      padding-left: 4px \9;
      /* IE7-8 doesn't have border-radius, so don't indent the padding */

      margin-bottom: 0;
      -webkit-border-radius: 3px;
      -moz-border-radius: 3px;
      border-radius: 3px;
    }

    #custom-search-form button {
      border: 0;
      background: none;
      /** belows styles are working good */
      padding: 2px 5px;
      margin-top: 2px;
      position: relative;
      left: -28px;
      /* IE7-8 doesn't have border-radius, so don't indent the padding */
      margin-bottom: 0;
      -webkit-border-radius: 3px;
      -moz-border-radius: 3px;
      border-radius: 3px;
    }

    .search-query:focus+button {
      z-index: 3;
    }
  </style>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/matrix.js"></script>

</body>

</html>