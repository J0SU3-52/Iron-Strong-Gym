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

  <?php $page = 'update-equip';
  include 'includes/sidebar.php' ?>

  <?php
  include 'dbcon.php';
  $id = $_GET['id'];
  $qry = "select * from equipment where id='$id'";
  $result = mysqli_query($con, $qry);
  while ($row = mysqli_fetch_array($result)) {
  ?>


    <div id="content">
      <div id="content-header">
        <div id="breadcrumb"> <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a> <a href="" class="tip-bottom">Equipos</a> <a href="#" class="current">Editar equipos</a> </div>
        <h1>Formulario de ingreso de equipo</h1>
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
                <form action="edit-equipment-req.php" method="POST" class="form-horizontal">
                  <div class="control-group">
                    <label class="control-label">Nombre del equipo :</label>
                    <div class="controls">
                      <input type="text" class="span11" name="name" value='<?php echo $row['name']; ?>' required />
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Descripción :</label>
                    <div class="controls">
                      <input type="text" class="span11" name="description" value='<?php echo $row['description']; ?>' required />
                    </div>
                  </div>


                  <div class="control-group">
                    <label class="control-label">Fecha de compra :</label>
                    <div class="controls">
                      <input type="date" name="date" value='<?php echo $row['date']; ?>' class="span11" />
                      <span class="help-block">Por favor mencione la fecha de compra.</span>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Cantidad :</label>
                    <div class="controls">
                      <input type="number" class="span4" name="quantity" value='<?php echo $row['quantity']; ?>' required />
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Vendor :</label>
                    <div class="controls">
                      <input type="text" class="span11" name="vendor" placeholder="iron" value='<?php echo $row['vendor']; ?>' required />
                    </div>
                  </div>

                  <div class="form-actions text-center">
                    <!-- user's ID is hidden here -->
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-success">Enviar detalles</button>
                  </div>
              </div>



              </form>

            </div>

          <?php
        }
          ?>


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

    <!--end-Footer-part-->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/matrix.js"></script>

</body>

</html>