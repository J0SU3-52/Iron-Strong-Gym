<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lista de Asistencia</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="../css/uniform.css" />
    <link rel="stylesheet" href="../css/select2.css" />
    <link rel="stylesheet" href="../css/matrix-style.css" />
    <link rel="stylesheet" href="../css/matrix-media.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>

<body>

    <div id="header">
        <h1><a href="dashboard.html"></a></h1>
    </div>

    <?php include 'includes/topheader.php' ?>
    <?php $page = "attendance";
    include 'includes/sidebar.php' ?>

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="attendance.php" class="current">Lista de Asistencia</a>
            </div>
            <h1 class="text-center">Lista de Asistencia <i class="fa-solid fa-calendar-check"></i></h1>
        </div>



        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">


                    <div class='widget-box'>
                        <div class='widget-title'> <span class='icon'> <i class='fas fa-th'></i> </span>
                            <h5>Tabla de Asistencia </h5>
                            <form id="custom-search-form" role="search" method="POST" action="search-view-attendance.php" class="form-search form-horizontal pull-right">
                                <div class="input-append span12">
                                    <input type="text" class="search-query" placeholder="Buscar" name="search" required>
                                    <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>





                        <div class='widget-content nopadding'>

                            <?php
                            include "dbcon.php";
                            $qry = "select * from members";
                            $cnt = 1;
                            $result = mysqli_query($con, $qry);

                            echo "<table class='table table-bordered table-hover'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre completo</th>

                            <th>Fecha de Inicio</th>
                            <th>Última Fecha de Pago</th>
                            <th>Fecha de Vencimiento</th>
                            <th>Servicio elegido</th>
                            <th>Acción</th>
                        </tr>
                    </thead>";

                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tbody> 
                        <td><div class='text-center'>" . $cnt . "</div></td>
                        <td><div class='text-center'>" . $row['fullname'] . "</div></td>   
                        <td><div class='text-center'>" . $row['dor'] . "</div></td>
                        <td><div class='text-center'>" . $row['paid_date'] . "</div></td>
                        <td><div class='text-center'>" . $row['next_date'] . "</div></td>
                        <td><div class='text-center'>" . $row['services'] . "</div></td>
                        <td>
                            <div class='text-center'><a href='calendar-attendance.php?id=" . $row['user_id'] . "'><button class='btn btn-success'>Ver Asistencia</button></a></div>
                        </td>
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