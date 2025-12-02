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

  <div id="search">
    <input type="hidden" placeholder="Search here..." />
    <button type="submit" class="tip-bottom" title="Search"><i class="fa-search fa-white"></i></button>
  </div>

  <?php $page = 'add-equip';
  include 'includes/sidebar.php' ?>


  <!--sidebar-menu-->
  <div id="content">
    <div id="content-header">
      <div id="breadcrumb">
        <a href="index.html" title="ir a inicio" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
        <a href="#" class="tip-bottom">Administrar clientes</a>
        <a href="#" class="current">Añadir clientes</a>
      </div>
      <h1>Formulario de Equipo</h1>
    </div>
    <form role="form" action="index.php" method="POST">


      <?php

      if (isset($_POST['ename'])) {
        $name = $_POST["ename"];
        $vendor = $_POST["vendor"];
        $description = $_POST["description"];
        $date = $_POST["date"];
        $quantity = $_POST["quantity"];


        include 'dbcon.php';

        $qry = "insert into equipment(name,description,vendor,date,quantity) values ('$name','$description','$vendor','$date','$quantity')";
        $result = mysqli_query($con, $qry);
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
          echo "<h3>Error occured while entering your details</h3>";
          echo "<p>Please Try Again</p>";
          echo "<a class='btn btn-warning btn-big'  href='edit-equipment.php'>Regresar</a> </div>";
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
          echo "<h1>Éxito</h1>";
          echo "<h3>¡Se ha agregado el registro de equipo!</h3>";
          echo "<p>Se añaden los datos solicitados. Por favor haga clic en el botón para regresar.</p>";
          echo "<a class='btn btn-inverse btn-big'  href='equipment.php'>Regresar</a> </div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
        }
      } else {
        echo "<h3>USTED NO ESTÁ AUTORIZADO A REDIRIGIR ESTA PÁGINA. Volver a<a href='index.php'> PANEL </a></h3>";
      }


      ?>




    </form>
  </div>
  </div>
  </div>
  </div>


  <div class="row-fluid">
    <div id="footer" class="span12"> <?php echo date("Y"); ?> &copy; Developed By Josue and Lazaro</a> </div>
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