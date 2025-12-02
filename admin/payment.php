<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('location:../index.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>Pagos</title>
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
  <?php $page = 'payment';
  include 'includes/sidebar.php' ?>
  <div id="content">
    <div id="content-header">
      <div id="breadcrumb"> <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
        <a href="payment.php" class="current">Pagos</a>
      </div>
      <h1 class="text-center">Pagos de Clientes <i class="fa-solid fa-money-bill-1 fa-lg"></i> </h1>
    </div>
    <div class="container-fluid">
      <hr>
      <div class="row-fluid">
        <div class="span12">
          <div class='widget-box'>
            <div class='widget-title'> <span class='icon'> <i class='fas fa-th'></i> </span>
              <h5>Tabla de Pagos de Clientes</h5>
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
              $qry = "SELECT * FROM members";
              $cnt = 1;
              $result = mysqli_query($con, $qry);
              echo "<table class='table table-bordered data-table table-hover'>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Cliente</th>
                  <th>Fecha de Inicio de Pago</th>
                  <th>Próxima Fecha de Pago</th>
                  <th>Última Fecha de Pago</th>
                  <th>Cantidad</th>
                  <th>Servicio elegido</th>
                  <th>Plan</th>
                  <th>Accion</th>
                  <th>Recordar Pago</th>
                </tr>
              </thead>";
              while ($row = mysqli_fetch_array($result)) { ?>
                <tbody>
                  <td>
                    <div class='text-center'><?php echo $cnt; ?></div>
                  </td>
                  <td>
                    <div class='text-center'><?php echo $row['fullname'] ?></div>
                  </td>
                  <td>
                    <div class='text-center'><?php echo $row['dor'] ?></div>
                  </td>
                  <td>
                    <div class='text-center'><?php echo ($row['next_date'] == 0 ? "Not Available" : $row['next_date']) ?></div>
                  </td>
                  <td>
                    <div class='text-center'><?php echo ($row['paid_date'] == 0 ? "New Member" : $row['paid_date']) ?></div>
                  </td>
                  <td>
                    <div class='text-center'><?php echo '$' . $row['amount'] ?></div>
                  </td>
                  <td>
                    <div class='text-center'><?php echo $row['services'] ?></div>
                  </td>
                  <td>
                    <div class='text-center'><?php echo $row['plan'] . " Mes/es" ?></div>
                  </td>
                  <td>
                    <div class='text-center'><a href='user-payment.php?id=<?php echo $row['user_id'] ?>'><button class='btn btn-success btn'><i class='fas fa-dollar-sign'></i> Realizar pago</button></a></div>
                  </td>
                  <td>
                    <div class='text-center'><a href='sendReminder.php?id=<?php echo $row['user_id'] ?>'><button class='btn btn-danger btn' <?php echo ($row['notify'] == 1 ? "disabled" : "") ?>>Notificar prox. pago</button></a></div>
                  </td>
                </tbody>
              <?php $cnt++;
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
      margin-bottom: 0;
      -webkit-border-radius: 3px;
      -moz-border-radius: 3px;
      border-radius: 3px;
    }

    #custom-search-form button {
      border: 0;
      background: none;
      padding: 2px 5px;
      margin-top: 2px;
      position: relative;
      left: -28px;
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