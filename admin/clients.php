<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('location:../index.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>Gestionar Clientes</title>
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
  <?php $page = "clients";
  include 'includes/sidebar.php' ?>


  <!--sidebar-menu-->

  <div id="content">
    <div id="content-header">
      <div id="breadcrumb"> <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
        <a href="" class="current">Clientes Registrados</a>
      </div>
      <h1 class="text-center">Clientes Registrados <i class="fas fa-users"></i>
      </h1>
    </div>

    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span12">

          <div class='widget-box'>
            <div class='widget-title'> <span class='icon'> <i class='fas fa-th'></i> </span>
              <h5>Tabla de clientes</h5>
              <form id="custom-search-form" role="search" method="POST" action="search-clients.php" class="form-search form-horizontal pull-right">
                <div class="input-append span12">
                  <input type="text" class="search-query" placeholder="Buscar" name="search" required>
                  <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                </div>
              </form>
            </div>

            <div class='widget-content nopadding'>

              <?php

              include "dbcon.php";
              $qry = "select *, gmail from members";
              $cnt = 1;
              $result = mysqli_query($con, $qry);


              echo "<table class='table table-bordered table-hover'>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre completo</th>
                  <th>Género</th>
                  <th>Fecha</th>
                  <th>Correo electrónico</th>
                  <th>Cantidad</th>
                  <th>Servicio elegido</th>
                  <th>Plan</th>
                  <th>Pin</th>
                </tr>
              </thead>";

              while ($row = mysqli_fetch_array($result)) {

                echo "<tbody> 
               
                <td><div class='text-center'>" . $cnt . "</div></td>
                <td><div class='text-center'>" . $row['fullname'] . "</div></td>
                <td><div class='text-center'>" . $row['gender'] . "</div></td>
                
                <td><div class='text-center'>" . $row['dor'] . "</div></td>
                <td><div class='text-center'>" . $row['gmail'] . "</div></td>
                <td><div class='text-center'>$" . $row['amount'] . "</div></td>
                <td><div class='text-center'>" . $row['services'] . "</div></td>
                <td><div class='text-center'>" . $row['plan'] . " Meses</div></td>
                <td><div class='text-center'>" . $row['pin'] . "</div></td>
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
    <div id="footer" class="span12"> <?php echo date("Y"); ?> &copy; Desarrollado por </a> </div>
  </div>

  <style>
    #footer {
      color: white;
    }
  </style>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/matrix.js"></script>

  <script type="text/javascript">
    function goPage(newURL) {
      if (newURL != "") {
        if (newURL == "-") {
          resetMenu();
        } else {
          document.location.href = newURL;
        }
      }
    }

    function resetMenu() {
      document.gomenu.selector.selectedIndex = 2;
    }
  </script>
</body>

</html>