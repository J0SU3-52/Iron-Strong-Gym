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
    <h1><a href=""></a></h1>
  </div>

  <?php include 'includes/topheader.php' ?>

  <?php $page = 'payment';
  include 'includes/sidebar.php' ?>


  <?php
  include 'dbcon.php';
  $id = $_GET['id'];
  $qry = "select * from members where user_id='$id'";
  $result = mysqli_query($con, $qry);
  while ($row = mysqli_fetch_array($result)) {
    $dor = $row['dor']; // Obtener la fecha de inicio de registro
  ?>

    <div id="content">
      <div id="content-header">
        <div id="breadcrumb">
          <a href="index.php" title="Go to Home" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
          <a href="payment.php">Pagos</a>
          <a href="#" class="current">Proceso de pago</a>
        </div>
        <h1>Proceso de pago</h1>
      </div>

      <div class="container-fluid" style="margin-top:-38px;">
        <div class="row-fluid">
          <div class="span12">
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="fas fa-money"></i> </span>
                <h5>Pagos</h5>
              </div>
              <div class="widget-content">
                <div class="row-fluid">
                  <div class="span5">
                    <table class="">
                      <tbody>
                        <tr>
                          <td>
                            <img style="margin-left: -65px;" src="../img/iron_strong5.png" width="400px">
                        </tr>
                        <tr>
                          <td>Av. Reforma Nte. 2804, Villas Campestre II 75719</td>
                        </tr>

                        <tr>
                          <td>Tel: 2381130605</td>
                        </tr>
                        <tr>
                          <td>Email: arturoosorioramirez0434@gmail.com </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="span7">
                    <table class="table table-bordered table-invoice">

                      <tbody>
                        <form action="userpay.php" method="POST">
                          <tr>

                          <tr>
                            <td class="width30">Nombre completo del cliente :</td>
                            <input type="hidden" name="fullname" value="<?php echo $row['fullname']; ?>">
                            <td class="width70"><strong><?php echo $row['fullname']; ?></strong></td>
                          </tr>

                          <tr>
                            <td class="width30">PIN de Entrada :</td>
                            <input type="hidden" name="pin" value="<?php echo $row['pin']; ?>">
                            <td class="width70"><strong><?php echo $row['pin']; ?></strong></td>
                          </tr>

                          <tr>
                            <td class="width30">Fecha de inicio :</td>
                            <input type="hidden" name="dor" value="<?php echo $dor; ?>">
                            <td class="width70"><strong><?php echo $dor; ?></strong></td>
                          </tr>

                          <tr>
                            <td>Servicio:</td>
                            <input type="hidden" name="services" value="<?php echo $row['services']; ?>">
                            <td><strong><?php echo $row['services']; ?></strong></td>
                          </tr>

                          <tr>
                            <td>Cantidad por mes:</td>
                            <td>
                              <?php echo $row['amount']; ?>
                              <input type="hidden" name="amount" value="<?php echo $row['amount']; ?>">
                            </td>
                          </tr>

                          <input type="hidden" name="paid_date" value="<?php echo date('Y-m-d'); ?>">

                          <td class="width30">Plan:</td>
                          <td class="width70">
                            <div class="controls">
                              <select name="plan" required="required" id="select">
                                <option value="1" <?php echo ($row['plan'] == 1) ? 'selected' : ''; ?>>Un mes</option>
                                <option value="2" <?php echo ($row['plan'] == 2) ? 'selected' : ''; ?>>Dos meses</option>
                                <option value="3" <?php echo ($row['plan'] == 3) ? 'selected' : ''; ?>>Tres meses</option>
                                <option value="6" <?php echo ($row['plan'] == 6) ? 'selected' : ''; ?>>Seis meses</option>
                                <option value="12" <?php echo ($row['plan'] == 12) ? 'selected' : ''; ?>>Un año</option>
                                <option value="0" <?php echo ($row['plan'] == 0) ? 'selected' : ''; ?>>Ninguno caducado</option>
                              </select>
                            </div>
                          </td>

                          <tr>

                          </tr>
                          <td class="width30">Estado del cliente :</td>
                          <td class="width70">
                            <div class="controls">
                              <select name="status" required="required" id="select">
                                <option value="Active" selected="selected">Activo</option>
                                <option value="Expired">Expirado</option>

                              </select>
                            </div>


                          </td>
                          </tr>
                      </tbody>

                    </table>
                  </div>
                </div>

                <div class="row-fluid">
                  <div class="span12">
                    <hr>
                    <div class="text-center">

                      <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">

                      <button class="btn btn-success btn-large" type="SUBMIT" href="">Hacer el pago</button>
                    </div>

                    </form>
                  </div>
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
    <div class="row-fluid">
      <div id="footer" class="span12"> <?php echo date("Y"); ?> &copy; Developed By Josué and Lazaro</a> </div>
    </div>

    <style>
      #footer {
        color: white;
      }
    </style>


    <script src="js/jquery.min.js"></script>
    <script src="js/matrix.js"></script>

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