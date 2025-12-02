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

  <?php $page = 'add-equip';
  include 'includes/sidebar.php' ?>
  <!--sidebar-menu-->

  <div id="content">
    <div id="content-header">
      <div id="breadcrumb">
        <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
        <a href="equipment.php" class="tip-bottom">Lista de Equipos del Gimnasio</a>
        <a href="" class="current">Agregar Equipo</a>
      </div>
      <h1>Agregar Equipo</h1>
    </div>


    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span6">
          <div class="widget-box">

            <div class="widget-title"> <span class="icon"> <i class="fas fa-align-justify"></i> </span>
              <h5>Información del equipo</h5>
            </div>


            <div class="widget-content nopadding">
              <form action="add-equipment-req.php" method="POST" class="form-horizontal">
                <div class="control-group">
                  <label class="control-label">Equipo :</label>
                  <div class="controls">
                    <input type="text" class="span11" name="ename" placeholder="nombre del equipo" required />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Descripcion :</label>
                  <div class="controls">
                    <input type="text" class="span11" name="description" placeholder="Breve descripción" required />
                  </div>
                </div>


                <div class="control-group">
                  <label class="control-label">Fecha de ingreso</label>
                  <div class="controls">
                    <input type="date" name="date" class="span11" />
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Cantidad :</label>
                  <div class="controls">
                    <input type="number" class="span5" name="quantity" placeholder="1" required />
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Marca:</label>
                  <div class="controls">
                    <input type="text" class="span11" name="vendor" placeholder="iron" required />
                  </div>
                </div>

                <div class="form-actions text-center">
                  <button type="submit" class="btn btn-success">Registrar Equipo</button>
                </div>
            </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>

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