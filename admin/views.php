<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Visitas</title>
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
    <?php $page = 'views';
    include 'includes/sidebar.php' ?>

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb">
                <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="" class="current">Pagos de Visitas</a>
            </div>
            <h1>Pagos de Visitas</h1>
        </div>

        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="fas fa-align-justify"></i> </span>
                            <h5>Información</h5>
                        </div>

                        <div class="widget-content nopadding">
                            <div class="form-horizontal">
                            </div>

                            <div class="widget-content nopadding">
                                <div class="form-horizontal">
                                    <form action="views-pay.php" method="POST" class="form-horizontal" id="paymentForm">
                                        <div class="control-group">
                                            <label class="control-label">Fecha de Visita:</label>
                                            <div class="controls">
                                                <input type="date" name="view_date" required="required" class="span11" />
                                                <span class="help-block">Fecha de Visita</span>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label for="normal" class="control-label">Planes: </label>
                                            <div class="controls">
                                                <select name="plan" required="required" id="planSelect">
                                                    <option value="1">Un dia</option>
                                                    <option value="2">Dos dias</option>
                                                    <option value="3">Tres dias</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Servicio</label>
                                            <div class="controls">
                                                <label>
                                                    <input type="text" value="Visita" name="services" required="required" />
                                                    <small></small></label>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">Cantidad</label>
                                            <div class="controls">
                                                <div class="input-append">
                                                    <span class="add-on">$</span>
                                                    <input type="number" placeholder="40" name="amount" required="required" class="span11">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions text-center">
                                            <button type="submit" class="btn btn-success">Realizar Pago</button>
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