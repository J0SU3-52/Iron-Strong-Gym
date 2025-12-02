<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('location:../index.php');
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

  <div id="header">
    <h1><a href="dashboard.html"></a></h1>
  </div>

  <?php include 'includes/topheader.php' ?>
  <?php $page = 'members-update';
  include 'includes/sidebar.php' ?>

  <?php
  include 'dbcon.php';
  $id = $_GET['id'];
  $qry = "SELECT * FROM members WHERE user_id='$id'";
  $result = mysqli_query($con, $qry);
  while ($row = mysqli_fetch_array($result)) {
    $selectedService = $row['services'];
  ?>

    <div id="content">
      <div id="content-header">
        <div id="breadcrumb">
          <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i>Home</a>
          <a href="clients-update.php" class="tip-bottom">Actualizar Clientes</a>
          <a href="" class="current">Actualizar detalles de clientes</a>
        </div>
        <h1>Actualizar detalles de clientes</h1>
      </div>

      <div class="container-fluid">
        <hr>
        <div class="row-fluid">
          <div class="span6">
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="fas fa-align-justify"></i> </span>
                <h5>Información de personal</h5>
              </div>
              <div class="widget-content nopadding">
                <form action="edit-member-req.php" method="POST" class="form-horizontal">
                  <div class="control-group">
                    <label class="control-label">Nombre completo :</label>
                    <div class="controls">
                      <input type="text" class="span11" name="fullname" value='<?php echo $row['fullname']; ?>' />
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Genero :</label>
                    <div class="controls">
                      <select name="gender" required="required" id="select">
                        <option value="Masculino" <?php if ($row['gender'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
                        <option value="Femenino" <?php if ($row['gender'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
                        <option value="Otro" <?php if ($row['gender'] == 'Otro') echo 'selected'; ?>>Otro</option>
                      </select>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Fecha :</label>
                    <div class="controls">
                      <input type="date" name="dor" class="span11" value='<?php echo $row['dor']; ?>' />
                      <span class="help-block">Fecha de registro</span>
                    </div>
                  </div>
              </div>
              <div class="widget-content nopadding">
                <div class="form-horizontal">
                </div>
                <div class="widget-content nopadding">
                  <div class="form-horizontal">
                    <div class="control-group">
                      <label for="normal" class="control-label">Planes: </label>
                      <div class="controls">
                        <select name="plan" required="required" id="select">
                          <option value="1" <?php if ($row['plan'] == 1) echo 'selected'; ?>>Un mes</option>
                          <option value="2" <?php if ($row['plan'] == 2) echo 'selected'; ?>>Dos Meses</option>
                          <option value="3" <?php if ($row['plan'] == 3) echo 'selected'; ?>>Tres Meses</option>
                          <option value="6" <?php if ($row['plan'] == 6) echo 'selected'; ?>>Seis Meses</option>
                          <option value="12" <?php if ($row['plan'] == 12) echo 'selected'; ?>>Un Año</option>
                        </select>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label">Pin de entrada</label>
                      <div class="controls">
                        <input type="number" name="pin" max="9999" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" value='<?php echo $row['pin']; ?>' class="icon" />
                        <input type="hidden" name="current_pin" value='<?php echo $row['pin']; ?>' />
                        <span id="pin-error" class="help-block text-danger" style="display:none;">PIN en uso</span>
                      </div>
                    </div>
                    <div class="control-group">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="span6">
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="fas fa-align-justify"></i> </span>
                <h5>Detalles de contacto</h5>
              </div>
              <div class="widget-content nopadding">
                <div class="form-horizontal">
                  <div class="control-group">
                    <label class="control-label">Correo electrónico</label>
                    <div class="controls">
                      <input type="text" class="span11" name="gmail" placeholder="Agregue su correo electronico" value='<?php echo $row['gmail']; ?>' />
                    </div>
                  </div>
                  <div class="widget-title"> <span class="icon"> <i class="fas fa-align-justify"></i> </span>
                    <h5>Detalles del servicio</h5>
                  </div>
                  <div class="widget-content nopadding">
                    <div class="form-horizontal">
                      <div class="control-group">
                        <label class="control-label">Servicios</label>
                        <div class="controls">
                          <label>
                            <input type="radio" value="Normal" name="services" <?php if ($selectedService == 'Normal') echo 'checked'; ?> />
                            Normal <small>- $400 por mes</small></label>
                          <label>
                            <input type="radio" value="Estudiante" name="services" <?php if ($selectedService == 'Estudiante') echo 'checked'; ?> />
                            Estudiante <small>- $300 por mes</small></label>
                          <label>
                            <input type="radio" value="Cardio" name="services" <?php if ($selectedService == 'Cardio') echo 'checked'; ?> />
                            Cardio <small>- $200 por mes</small></label>
                          <label>
                            <input type="radio" value="Normal + Cardio" name="services" <?php if ($selectedService == 'Normal + Cardio') echo 'checked'; ?> />
                            Normal + Cardio <small>- $500 por mes</small></label>
                          <label>
                            <input type="radio" value="Estudiante + Cardio" name="services" <?php if ($selectedService == 'Estudiante + Cardio') echo 'checked'; ?> />
                            Estudiante + Cardio<small>- $400 por mes</small></label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Cantidad total</label>
                        <div class="controls">
                          <div class="input-append">
                            <span class="add-on">$</span>
                            <input type="number" value='<?php echo $row['amount']; ?>' name="amount" class="span11">
                          </div>
                        </div>
                      </div>
                      <div class="form-actions text-center">
                        <!-- user's ID is hidden here -->
                        <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">
                        <button type="submit" class="btn btn-success">Actualizar detalles del cliente</button>
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