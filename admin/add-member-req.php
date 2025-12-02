<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('location:../index.php');
  exit();
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
        <a href="index.html" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
        <a href="clients.php" class="tip-bottom">Administrar clientes</a>
        <a href="#" class="current">Añadir clientes</a>
      </div>
      <h1>Registro de clientes</h1>
    </div>
    <form role="form" action="index.php" method="POST">
      <?php
      include 'dbcon.php';

      if (isset($_POST['fullname'])) {
        $fullname = $_POST["fullname"];
        $dor = $_POST["dor"];
        $gender = $_POST["gender"];
        $services = $_POST["services"];
        $gmail = $_POST["gmail"];
        $amount = $_POST["amount"];
        $p_year = date('Y');
        $paid_date = date("Y-m-d");
        $plan = $_POST["plan"];
        //$contact = $_POST["contact"];
        $pin = isset($_POST["pin"]) && !empty($_POST["pin"]) ? $_POST["pin"] : '0000'; // Valor predeterminado si está vacío
        $status = 'Pending Reg'; // Estado inicial de nuevo cliente

        // Calcular la próxima fecha de pago
        $next_date = date('Y-m-d', strtotime("+$plan months", strtotime($dor)));

        // Verificar si el PIN ya existe
        $check_pin_query = "SELECT * FROM members WHERE pin = '$pin'";
        $check_pin_result = mysqli_query($con, $check_pin_query);

        if (mysqli_num_rows($check_pin_result) > 0) {
          echo "<div id='pin-alert' class='alert alert-danger' role='alert'>PIN en uso intenta con otro</div>";
          echo "<script>
            setTimeout(function() {
              document.getElementById('pin-alert').style.display = 'none';
              window.history.back();
            }, 2000);
          </script>";
        } else {
          // Consulta de inserción
          $qry = "INSERT INTO members (fullname, dor, gender, gmail, services, amount, p_year, paid_date, plan, pin, next_date, status) 
            VALUES ('$fullname', '$dor', '$gender', '$gmail', '$services', '$amount', '$p_year', '$paid_date', '$plan', '$pin', '$next_date', '$status')";

          if (mysqli_query($con, $qry)) {
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
            echo "<h3>¡Se han agregado los detalles del cliente!</h3>";
            echo "<p>Se añaden los datos solicitados. Por favor haga clic en el botón para regresar.</p>";
            echo "<a class='btn btn-inverse btn-big' href='clients.php'>Regresar</a> </div>";
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
            echo "<h5>Error Message</h5>";
            echo "</div>";
            echo "<div class='widget-content'>";
            echo "<div class='error_ex'>";
            echo "<h1 style='color:maroon;'>Error 404</h1>";
            echo "<h3>Ocurrió un error al ingresar sus datos</h3>";
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
            echo "<p>Inténtalo de nuevo</p>";
            echo "<a class='btn btn-warning btn-big' href='clients-entry.php'>Regresar</a> </div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          }
        }
      } else {
        echo "<h3>USTED NO ESTÁ AUTORIZADO A REDIRIGIR ESTA PÁGINA. Volver a <a href='index.php'> PANEL </a></h3>";
      }
      ?>
    </form>
  </div>

  <div class="row-fluid">
    <div id="footer" class="span12">
      <?php echo date("Y"); ?> &copy; Developed By Josué and Lazaro
    </div>
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

  <script src="../js/excanvas.min.js"></script>
  <script src="../js/jquery.min.js"></script>
  <script src="../js/jquery.ui.custom.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery.flot.min.js"></script>
  <script src="../js/jquery.flot.resize.min.js"></script>
  <script src="../js/jquery.peity.min.js"></script>
  <script src="../js/fullcalendar.min.js"></script>
  <script src="../js/matrix.js"></script>
  <script src="../js/matrix.dashboard.js"></script>
  <script src="../js/jquery.gritter.min.js"></script>
  <script src="../js/matrix.interface.js"></script>
  <script src="../js/matrix.chat.js"></script>
  <script src="../js/jquery.validate.js"></script>
  <script src="../js/matrix.form_validation.js"></script>
  <script src="../js/jquery.wizard.js"></script>
  <script src="../js/jquery.uniform.js"></script>
  <script src="../js/select2.min.js"></script>
  <script src="../js/matrix.popover.js"></script>
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/matrix.tables.js"></script>

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