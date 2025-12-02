<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Estado del cliente</title>
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
    <?php $page = 'member-status';
    include 'includes/sidebar.php' ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb">
                <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="member-status.php" class="current">Estado</a>
            </div>
            <h1 class="text-center">Estado actual del cliente <i class="fas fa-eye"></i></h1>
        </div>
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class='widget-box'>
                        <div>
                            <a href='clients-active.php'><button class='btn-success btn-left'>
                                    Clientes activos <i class="fa-solid fa-check fa-lg"></i></button>
                            </a>

                            <a href='clients-expired.php'><button class='btn-danger btn-left'>
                                    Clientes expirados <i class="fa-solid fa-skull-crossbones fa-lg"></i></button>
                            </a>

                            <a href='clients-male.php'><button class='btn btn-primary btn-blue'>
                                    Clientes Masculinos <i class="fas fa-male fa-lg"></i></button>
                            </a>

                            <a href='clients-female.php'><button class='btn btn-success btn-pink'>
                                    Clientes Femeninos <i class="fas fa-female fa-lg"></i></button>
                            </a>
                        </div>

                        <style>
                            .btn-pink {
                                background-color: plum;
                                border-color: plum !important;
                                color: white !important;
                            }

                            .btn-blue {
                                background-color: dodgerblue;

                            }
                        </style>
                        <div class='widget-title'> <span class='icon'> <i class='fas fa-th'></i> </span>
                            <h5>Tabla de estado</h5>
                            <form id="custom-search-form" role="search" method="POST" action="search-member-status.php" class="form-search form-horizontal pull-right">
                                <div class="input-append span12">
                                    <input type="text" class="search-query" placeholder="Buscar" name="search" required>
                                    <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div>
                        <?php
                        include "dbcon.php";
                        $qry = "SELECT * FROM members WHERE status='Active'";
                        $cnt = 1;
                        $result = mysqli_query($con, $qry);
                        echo "<table class='table table-bordered table-hover data-table'>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre completo</th>
                  <th>Servicio elegido</th>
                  <th>Pin</th>
                  <th>Plan</th>
                  <th>Fecha de Pago</th>
                  <th>Estado de Mensualidad</th>
                  <th>Fecha de Vencimiento</th>
                </tr>
              </thead>";
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class='text-center'><?php echo $cnt; ?></div>
                                    </td>
                                    <td>
                                        <div class='text-center'><?php echo $row['fullname']; ?></div>
                                    </td>
                                    <td>
                                        <div class='text-center'><?php echo $row['services']; ?></div>
                                    </td>
                                    <td>
                                        <div class='text-center'><?php echo $row['pin']; ?></div>
                                    </td>
                                    <td>
                                        <div class='text-center'><?php echo $row['plan'] . " meses"; ?></div>
                                    </td>

                                    <td>
                                        <div class='text-center'><?php echo $row['paid_date']; ?></div>
                                    </td>
                                    <td>
                                        <div class='text-center'><?php if ($row['status'] == 'Active') {
                                                                        echo '<i class="fas fa-circle" style="color:green;"></i> Activa';
                                                                    } ?></div>
                                    </td>
                                    <td>
                                        <div class='text-center'><?php echo $row['next_date']; ?></div>
                                    </td>
                                </tr>
                            </tbody>
                        <?php
                            $cnt++;
                        }
                        echo "</table>";
                        ?>
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
    </style>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/matrix.js"></script>
</body>

</html>