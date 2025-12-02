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
  <?php $page = 'clients-entry';
  include 'includes/sidebar.php' ?>

  <div id="content">
    <div id="content-header">
      <div id="breadcrumb">
        <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a> <a href="clients.php" class="tip-bottom">Administrar clientes</a>
        <a href="#" class="current">Añadir clientes</a>
      </div>
      <h1>Registro de clientes</h1>
    </div>
    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span6">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="fas fa-align-justify"></i> </span>
              <h5>Información personal</h5>
            </div>
            <div class="widget-content nopadding">
              <form id="member-form" action="add-member-req.php" method="POST" class="form-horizontal">

                <div class="control-group">
                  <label class="control-label">Nombre Completo</label>
                  <div class="controls">
                    <input type="text" class="span11" name="fullname" value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : ''; ?>" required="required" placeholder="Ingrese su nombre con apellidos" />
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label">Género :</label>
                  <div class="controls">
                    <select name="gender" required="required" id="select">
                      <option value="Masculino" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Masculino') ? 'selected="selected"' : ''; ?>>Masculino</option>
                      <option value="Femenino" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Femenino') ? 'selected="selected"' : ''; ?>>Femenino</option>
                      <option value="Otro" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Otro') ? 'selected="selected"' : ''; ?>>Otro</option>
                    </select>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Fecha :</label>
                  <div class="controls">
                    <input type="date" name="dor" value="<?php echo isset($_POST['dor']) ? $_POST['dor'] : ''; ?>" required="required" class="span11" />
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
                        <option value="1" <?php echo (isset($_POST['plan']) && $_POST['plan'] == '1') ? 'selected="selected"' : ''; ?>>Un mes</option>
                        <option value="2" <?php echo (isset($_POST['plan']) && $_POST['plan'] == '2') ? 'selected="selected"' : ''; ?>>Dos meses</option>
                        <option value="3" <?php echo (isset($_POST['plan']) && $_POST['plan'] == '3') ? 'selected="selected"' : ''; ?>>Tres meses</option>
                        <option value="6" <?php echo (isset($_POST['plan']) && $_POST['plan'] == '6') ? 'selected="selected"' : ''; ?>>Seis meses</option>
                        <option value="12" <?php echo (isset($_POST['plan']) && $_POST['plan'] == '12') ? 'selected="selected"' : ''; ?>>Un año</option>
                      </select>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Pin de entrada</label>
                    <div class="controls">
                      <input type="number" class="icon" name="pin" id="pin" required="required" max="9999" placeholder="Ingrese su pin (4 dígitos)" oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" />
                      <span id="pin-error" class="help-block text-danger" style="display:none;">PIN en uso</span>
                    </div>
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
                <!-- <div class="control-group">
                  <label for="normal" class="control-label">Número telefónico</label>
                  <div class="controls">
                    <input type="number" id="mask-phone" name="contact" value="<?php echo isset($_POST['contact']) ? $_POST['contact'] : ''; ?>" required="required" placeholder="" class="span8 mask text" max="9999999999" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);">
                    <span class="help-block blue span8"></span>
                  </div>
                </div> -->

                <div class="control-group">
                  <label class="control-label">Correo electrónico:</label>
                  <div class="controls">
                    <input type="text" class="span11" name="gmail" value="<?php echo isset($_POST['gmail']) ? $_POST['gmail'] : ''; ?>" required="required" placeholder="Agregue su correo electrónico " />
                  </div>
                </div>
              </div>

              <div class="widget-title"> <span class="icon"> <i class="fas fa-align-justify"></i> </span>
                <h5>Detalles de servicio</h5>
              </div>
              <div class="widget-content nopadding">
                <div class="form-horizontal">
                  <div class="control-group">
                    <label class="control-label">Servicios</label>
                    <div class="controls">
                      <label>
                        <input type="radio" value="Normal" name="services" <?php echo (isset($_POST['services']) && $_POST['services'] == 'Normal') ? 'checked' : ''; ?> required="required" />
                        Normal <small>- $400 por mes</small></label>
                      <label>
                        <input type="radio" value="Estudiante" name="services" <?php echo (isset($_POST['services']) && $_POST['services'] == 'Estudiante') ? 'checked' : ''; ?> required="required" />
                        Estudiante <small>- $350 por mes</small></label>
                      <label>
                        <input type="radio" value="Cardio" name="services" <?php echo (isset($_POST['services']) && $_POST['services'] == 'Cardio') ? 'checked' : ''; ?> required="required" />
                        Cardio <small>- $200 por mes</small></label>
                      <label>
                        <input type="radio" value="Normal + Cardio" name="services" <?php echo (isset($_POST['services']) && $_POST['services'] == 'Normal + Cardio') ? 'checked' : ''; ?> required="required" />
                        Normal + Cardio <small>- $500 por mes</small></label>
                      <label>
                        <input type="radio" value="Estudiante + cardio" name="services" <?php echo (isset($_POST['services']) && $_POST['services'] == 'Estudiante + cardio') ? 'checked' : ''; ?> required="required" />
                        Estudiante + Cardio<small>- $400 por mes</small></label>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label">Cantidad total</label>
                    <div class="controls">
                      <div class="input-append">
                        <span class="add-on">$</span>
                        <input type="number" placeholder="400" name="amount" value="<?php echo isset($_POST['amount']) ? $_POST['amount'] : ''; ?>" required="required" class="span11">
                      </div>
                    </div>
                  </div>

                  <input type="hidden" name="status" value="Pending Reg">

                  <div class="form-actions text-center">
                    <button type="submit" class="btn btn-success">Registrar datos del cliente</button>
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

  <div class="row-fluid">
    <div id="footer" class="span12"> <?php echo date("Y"); ?> &copy; Developed By Josué and Lazaro</div>
  </div>

  <style>
    #footer {
      color: white;
    }

    .alert {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1000;
      display: block;
    }
  </style>

  <script src="../js/jquery.min.js"></script>
  <script src="../js/matrix.js"></script>

  <script>
    $(document).ready(function() {
      $("#pin").on('input', function() {
        var pin = $(this).val();
        if (pin.length === 4) {
          $.ajax({
            url: 'check_pin.php',
            method: 'POST',
            data: {
              pin: pin
            },
            success: function(response) {
              if (response === 'taken') {
                $("#pin-error").show();
                $("#pin-error").text("PIN en uso");
                $("#pin").data('pin-taken', true);
              } else {
                $("#pin-error").hide();
                $("#pin-error").text("");
                $("#pin").data('pin-taken', false);
              }
            }
          });
        } else {
          $("#pin-error").hide();
          $("#pin").data('pin-taken', false);
        }
      });

      $("#member-form").on('submit', function(e) {
        if ($("#pin").data('pin-taken')) {
          e.preventDefault();
          $("#pin-error").show();
        }
      });
    });
  </script>
</body>

</html>