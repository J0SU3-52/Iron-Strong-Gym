<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Rutinas</title>
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
    <style>
        #footer {
            color: white;
        }

        .form-search-wrapper {
            float: right;
            margin-top: 0px;
        }

        #custom-search-form {
            margin: 0;
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

        .widget-box {
            margin-left: auto;
            margin-right: auto;
            width: 98%;
            /* Ajusta el porcentaje según lo necesites */
        }
    </style>
</head>

<body>

    <div id="header">
        <h1><a href="dashboard.html"></a></h1>
    </div>
    <?php include 'includes/topheader.php' ?>

    <?php $page = 'update-routine.php';
    include 'includes/sidebar.php' ?>

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="routine.php">Rutinas personalizadas</a>
                <a href="search-routines-update.php" class="current">Actualizar rutinas</a>
            </div>
            <h1 class="text-center">Actualizar Rutinas <i class="fas fa-dumbbell"></i></h1>
        </div>
        <div class='widget-box'>
            <div class='widget-title'> <span class='icon'> <i class='fas fa-th'></i> </span>
                <h5>Tabla de Rutinas</h5>
                <div class="form-search-wrapper">
                    <form id="custom-search-form" role="search" method="POST" action="search-routines-update.php" class="form-search">
                        <div class="input-append">
                            <input type="text" class="search-query" placeholder="Buscar" name="search" required>
                            <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class='widget-content nopadding'>
                <?php
                include "dbcon.php";
                $search = $_POST['search'];
                $cnt = 1;
                $qry = "SELECT * FROM routines WHERE fullname LIKE '%$search%' OR routine_name LIKE '%$search%'";
                $result = mysqli_query($con, $qry);

                if (mysqli_num_rows($result) == 0) {
                    echo "<div class='error_ex'>
                        <h1>403</h1>
                        <h3>Opps, Rutina no encontrada!!</h3>
                        <p>Parece que no existe tal registro disponible en la base de datos.</p>
                        <a class='btn btn-danger btn-big' href='routine.php'>Regresar</a>
                    </div>";
                } else {
                    echo "<table class='table table-bordered table-hover'>
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Nombre completo</th>
                            <th>Nombre de la Rutina</th>
                            <th>Fecha de creación</th>
                            <th>Correo electrónico</th>
                            <th>Servicio elegido</th>
                            <th>Acción</th>
                          </tr>
                        </thead>";
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tbody> 
                            <td><div class='text-center'>" . $cnt . "</div></td>
                            <td><div class='text-center'>" . $row['fullname'] . "</div></td>
                            <td><div class='text-center'>" . $row['routine_name'] . "</div></td>
                            <td><div class='text-center'>" . $row['dor'] . "</div></td>
                            <td><div class='text-center'>" . $row['gmail'] . "</div></td>
                            <td><div class='text-center'>" . $row['services'] . "</div></td>
                            <td><div class='text-center'><a href='edit-routine.php?id=" . $row['routine_id'] . "' style='color:blue;'><i class='fas fa-edit'></i> Editar</a></div></td>
                          </tbody>";
                        $cnt++;
                    }
                    echo "</table>";
                }
                ?>
            </div>
        </div>
        <div class="row-fluid">
        </div>
    </div>
    <!--end-main-container-part-->

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