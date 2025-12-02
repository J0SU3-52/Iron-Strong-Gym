<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
    exit();
}

require __DIR__ . '/../vendor/autoload.php'; // Asegúrate de que esta ruta es correcta

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

function printReceipt($view_date, $services, $amount, $plan, $total_amount)
{
    try {
        // Conectar a la impresora (ajusta el nombre de la impresora según sea necesario)
        $connector = new WindowsPrintConnector("Iron Strong Printer"); // Reemplaza "Iron Strong" con el nombre de tu impresora
        $printer = new Printer($connector);

        // Imprimir encabezado
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_EMPHASIZED);
        $printer->text("IRON STRONG GYM\n");
        $printer->selectPrintMode(); // Restablecer a modo normal
        $printer->text("                                ");
        $printer->text("      Av. Reforma Nte. 2804,     ");
        $printer->text("Villas Campestre II 75719\n");
        $printer->text("Tehuacán, Puebla\n");
        $printer->text("Folio: " . rand(100000, 1000) . "\n");
        $printer->text("Fecha: " . date('d, M Y H:i:s') . "\n");
        $printer->text("--------------------------------\n");

        // Imprimir detalles del cliente y pago
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Fecha de Visita: $view_date\n");
        $printer->text("Servicio: $services\n");
        $printer->text("Cantidad: $$amount\n");
        $printer->text("Plan: $plan día(s)\n");
        $printer->text("--------------------------------\n");
        $printer->text("Cantidad total: $$total_amount\n");

        // Cierre de la impresora
        $printer->cut(Printer::CUT_PARTIAL); // Corte parcial
        $printer->close();
    } catch (Exception $e) {
        echo "No se puede imprimir en la impresora: " . $e->getMessage() . "\n";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST["amount"];
    $services = $_POST["services"];
    $plan = $_POST["plan"];
    $view_date = $_POST["view_date"];

    // Precio por visita
    $price_per_day = 40;

    // Calcular la cantidad total basada en el plan seleccionado
    $total_amount = $plan * $price_per_day;

    // Incluye la conexión a la base de datos
    include 'dbcon.php';

    // Consulta para insertar los datos en la base de datos
    $qry = "INSERT INTO views (amount, services, plan, view_date) VALUES ('$amount', '$services', '$plan', '$view_date')";

    // Ejecuta la consulta
    $result = mysqli_query($con, $qry);

    // Verifica si la inserción fue exitosa
    if ($result) {
        $status = 'Active';
    } else {
        $status = 'Failed';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Baucher Visitas</title>
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
                <a href="views.php" class="tip-bottom">Pagos de Visitas</a>
                <a href="" class="current">Baucher Visitas</a>
            </div>
            <h1>Baucher Visitas</h1>
        </div>
        <form role="form" action="index.php" method="POST">
            <?php if (isset($status) && $status == 'Active') { ?>
                <table class="body-wrap">
                    <tbody>
                        <tr>
                            <td></td>
                            <td class="container" width="600">
                                <div class="content">
                                    <table class="main" width="100%" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td class="content-wrap aligncenter print-container">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td class="content-block">
                                                                    <h3 class="text-center" style="display: flex; justify-content: space-around; align-items: center; margin-right: -35px;">Recibo de Pago</h3>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block">
                                                                    <table class="invoice">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <div style="float:left"><strong>IRON STRONG GYM</strong> Folio:<?php echo (rand(100000, 1000)); ?> <br> Av. Reforma Nte. 2804, Villas Campestre II 75719, <br>Tehuacan, Puebla.</div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="text-center" style="font-size:14px;"><br>
                                                                                    Pagó en <?php echo  date_default_timezone_set('America/Mexico_City');
                                                                                            $current_date = date('Y-m-d h:i A');
                                                                                            $exp_date_time = explode(' ', $current_date);
                                                                                            $curr_date =  $exp_date_time['0'];
                                                                                            $curr_time =  $exp_date_time['1'] . ' ' . $exp_date_time['2']; ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td><b>Servicio tomado</b></td>
                                                                                                <td class="alignright"><b>Valido hasta</b></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td><?php echo $services; ?></td>
                                                                                                <td class="alignright"><?php echo $plan . " día(s)"; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td><?php echo 'Pago por plan'; ?></td>
                                                                                                <td class="alignright"><?php echo '$' . $price_per_day; ?></td>
                                                                                            </tr>
                                                                                            <tr class="total">
                                                                                                <td class="alignright" width="80%">Cantidad total</td>
                                                                                                <td class="alignright"><?php echo '$' . $total_amount; ?></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block text-center">
                                                                    Apreciamos sinceramente su prontitud con respecto a todos los pagos de su parte.
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="footer">
                                        <table width="100%">
                                            <tbody>
                                                <tr>
                                                    <td class="aligncenter content-block"><a class='btn btn-danger btn-big' href='views.php'>Regresar</a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <?php
                // Llamar a la función de impresión
                printReceipt($view_date, $services, $amount, $plan, $total_amount);
                ?>
            <?php } else { ?>
                <h3 class="text-center">¡Algo salió mal!</h3>
            <?php } ?>
        </form>
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

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6;
        }

        table td {
            vertical-align: top;
        }

        .body-wrap {
            background-color: #f6f6f6;
            width: 100%;
        }

        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            clear: both !important;
        }

        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
            padding: 20px;
        }

        .main {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 3px;
        }

        .content-wrap {
            padding: 20px;
        }

        .footer {
            width: 100%;
            clear: both;
            color: #999;
            padding: 20px;
        }

        .invoice {
            margin: 22px auto;
            text-align: left;
            width: 80%;
        }

        .invoice td {
            padding: 7px 0;
        }

        .invoice .invoice-items {
            width: 100%;
        }

        .invoice .invoice-items td {
            border-top: #eee 1px solid;
        }

        .invoice .invoice-items .total td {
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            font-weight: 700;
        }

        @media only screen and (max-width: 640px) {
            h2 {
                font-size: 18px !important;
            }

            .container {
                width: 100% !important;
            }

            .content,
            .content-wrap {
                padding: 10px !important;
            }

            .invoice {
                width: 100% !important;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .print-container {
                position: absolute;
                left: 0px;
                top: 0px;
                right: 0px;
            }
        }
    </style>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/matrix.js"></script>
</body>

</html>