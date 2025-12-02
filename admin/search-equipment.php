<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Equipos del gimnasio</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="../css/fullcalendar.css" />
    <link rel="stylesheet" href="../css/matrix-style.css" />
    <link rel="stylesheet" href="../css/matrix-media.css" />
    <link href="../font-awesome/css/fontawesome.css" rel="stylesheet" />
    <link href="../font-awesome/css/all.css" rel="stylesheet" />
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

    <?php $page = "equipment.php";
    include 'includes/sidebar.php' ?>


    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="equipment.php">Lista de Equipos del Gimnasio</a>
                <a href="#" class="current">Resultados Busqueda</a>
            </div>
            <h1 class="text-center">Lista de Equipos del Gimnasio <i class="fas fa-dumbbell"></i></h1>
        </div>
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">

                    <div class='widget-box'>
                        <div class='widget-title'> <span class='icon'> <i class='fas fa-th'></i> </span>
                            <h5>Tabla de equipo</h5>
                            <form id="custom-search-form" role="search" method="POST" action="search-equipment.php" class="form-search form-horizontal pull-right">
                                <div class="input-append span12">
                                    <input type="text" class="search-query" placeholder="Buscar" name="search" required>
                                    <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <div class='widget-content nopadding'>
                            <?php

                            include "dbcon.php";
                            $search = $_POST['search'];
                            $cnt = 1;
                            $qry = "select * from equipment where name like '%$search%' or vendor like '%$search%'";
                            $result = mysqli_query($con, $qry);

                            if (mysqli_num_rows($result) == 0) {

                                echo "<div class='error_ex'>
            <h1>403</h1>
            <h3>Opps, Equipo no encontrado!!</h3>
            <p>Parece que no existe tal registro disponible en la base de datos.</p>
            <a class='btn btn-danger btn-big'  href='equipment.php'>Regresar</a> </div>'";
                            } else {

                                echo "<table class='table table-bordered table-hover'>
        <thead>
                <tr>
                  <th>#</th>
                  <th>E. Nombre</th>
                  <th>Descripcion</th>
                  <th>Cantidad</th>
                  <th>Marca</th>
                  <th>Fecha de ingreso</th>
                </tr>
        </thead>";


                                while ($row = mysqli_fetch_array($result)) {



                                    echo "<tbody> 
               
                <td><div class='text-center'>" . $cnt . "</div></td>
                <td><div class='text-center'>" . $row['name'] . "</div></td>
                <td><div class='text-center'>" . $row['description'] . "</div></td>
                <td><div class='text-center'>" . $row['quantity'] . "</div></td>
                <td><div class='text-center'>" . $row['vendor'] . "</div></td>
                <td><div class='text-center'>" . $row['date'] . "</div></td>
                
              </tbody>";
                                    $cnt++;
                                }
                            }
                            ?>

                            </table>
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

    <style>
        #footer {
            color: white;
        }
    </style>

    <!--end-Footer-part-->

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
            /* IE7-8 doesn't have border-radius, so don't indent the padding */

            margin-bottom: 0;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        }

        #custom-search-form button {
            border: 0;
            background: none;
            /** belows styles are working good */
            padding: 2px 5px;
            margin-top: 2px;
            position: relative;
            left: -28px;
            /* IE7-8 doesn't have border-radius, so don't indent the padding */
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