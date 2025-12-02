<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
    exit();
}

require __DIR__ . '/../vendor/autoload.php'; // Asegúrate de que esta ruta es correcta

use GuzzleHttp\Client;

function printReceipt($fullname, $pin, $next_date, $services, $amount, $plan, $amountpayable)
{
    $apiKey = '0JIJp-8IVEZNiEacmOQGj7hJwIk7tTs5CjNrOnGitwA'; // Reemplaza con tu API Key de PrintNode
    $printerId = '73498116'; // ID de la impresora "Iron Strong Printer" en PrintNode

    $ticketData = "\x1B\x61\x01"; // Center alignment
    $ticketData .= "\x1B\x21\x30"; // Double height and width
    $ticketData .= "IRON STRONG GYM\n";
    $ticketData .= "\x1B\x21\x00"; // Cancel double height and width
    $ticketData .= "\x1B\x61\x00"; // Left alignment
    $ticketData .= "                                ";
    $ticketData .= "      Av. Reforma Nte. 2804,   ";
    $ticketData .= "     Villas Campestre II 75719\n";
    $ticketData .= "       Tehuacan, Puebla\n";
    $ticketData .= "         Folio: " . rand(100000, 1000) . "\n";
    $ticketData .= "Fecha: " . date('d, M Y H:i:s') . "\n";
    $ticketData .= "--------------------------------\n";
    $ticketData .= "Cliente: $fullname\n";
    $ticketData .= "PIN de Entrada: $pin\n";
    $ticketData .= "Fecha de Corte: $next_date\n";
    $ticketData .= "Servicio: $services\n";
    $ticketData .= "Cantidad por mes: $$amount\n";
    $ticketData .= "Plan: $plan Mes/es\n";
    $ticketData .= "--------------------------------\n";
    $ticketData .= "Cantidad total: $$amountpayable\n";
    $ticketData .= "\x1B\x21\x01"; // Comando ESC/POS para tamaño de fuente más pequeño
    $ticketData .= "     Conserva este ticket por cualquier                duda o aclaracion.";
    $ticketData .= "\n\n\n";


    try {
        $client = new Client([
            'base_uri' => 'https://api.printnode.com/',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($apiKey)
            ],
            'verify' => 'C:/wamp64/cacert.pem' // Ruta al archivo de certificados CA
        ]);

        $response = $client->post('printjobs', [
            'json' => [
                'printerId' => $printerId,
                'title' => 'Ticket de Pago',
                'contentType' => 'raw_base64',
                'content' => base64_encode($ticketData),
                'source' => 'PHP API Client'
            ]
        ]);

        if ($response->getStatusCode() == 201) {
            echo "Recibo enviado a la impresora correctamente .-.";
        } else {
            echo "Error al enviar el recibo a la impresora: " . $response->getReasonPhrase();
        }
    } catch (Exception $e) {
        echo "No se puede imprimir en la impresora: " . $e->getMessage() . "\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

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

    <!--Header-part-->
    <div id="header">
        <h1><a href="dashboard.html"></a></h1>
    </div>
    <!--close-Header-part-->

    <!--top-Header-menu-->
    <?php include 'includes/topheader.php' ?>

    <!--close-top-serch-->

    <!--sidebar-menu-->
    <?php $page = 'payment';
    include 'includes/sidebar.php' ?>
    <!--sidebar-menu-->

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb">
                <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="payment.php" class="tip-bottom">Pago</a>
                <a href="#" class="current">Recibo de Pago</a>
            </div>
            <h1>Recibo de Pago</h1>
        </div>
        <form role="form" action="index.php" method="POST">
            <?php

            if (isset($_POST['amount'])) {

                $fullname = $_POST['fullname'];
                $pin = $_POST['pin'];
                $services = $_POST["services"];
                $amount = $_POST["amount"];
                $plan = $_POST["plan"];
                $status = $_POST["status"];
                $id = $_POST['id'];

                $amountpayable = $amount * $plan;

                include 'dbcon.php';
                date_default_timezone_set('America/Mexico_City');
                $current_date = date('Y-m-d h:i A');
                $exp_date_time = explode(' ', $current_date);
                $curr_date =  $exp_date_time['0'];
                $curr_time =  $exp_date_time['1'] . ' ' . $exp_date_time['2'];

                // Calcular la nueva fecha de corte
                $next_date = date('Y-m-d', strtotime("+$plan months", strtotime($curr_date)));

                // Actualizar la base de datos
                $qry = "UPDATE members SET amount='$amountpayable', plan='$plan', status='$status', paid_date='$curr_date', next_date='$next_date', notify='0' WHERE user_id='$id'";
                $result = mysqli_query($con, $qry);

                if (!$result) { ?>

                    <h3 class="text-center">¡Algo salió mal!</h3>

                <?php } else { ?>

                    <?php if ($status == 'Active') { ?>

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
                                                                                            <div style="float:left"><strong>IRON STRONG GYM</strong> Folio:<?php echo (rand(100000, 1000)); ?> <br> Av. Reforma Nte. 2804, Villas Campestre II 75719, <br>Tehuacán, Puebla.</div>

                                                                                        </td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <td class="text-center" style="font-size:14px;"><b>Cliente: <?php echo $fullname; ?></b> <br>
                                                                                            Pagó en <?php echo date('d, M Y H:i:s'); ?>

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
                                                                                                        <td>PIN De Entrada</td>
                                                                                                        <td class="alignright"><?php echo $pin ?> </td>
                                                                                                    </tr>

                                                                                                    <tr>
                                                                                                        <td>Fecha de Corte</td>
                                                                                                        <td class="alignright"><?php echo $next_date ?> </td>
                                                                                                    </tr>

                                                                                                    <tr>
                                                                                                        <td><?php echo $services; ?></td>
                                                                                                        <td class="alignright"><?php echo $plan ?> Meses</td>
                                                                                                    </tr>

                                                                                                    <tr>
                                                                                                        <td><?php echo 'Pago por mes'; ?></td>
                                                                                                        <td class="alignright"><?php echo '$' . $amount ?></td>
                                                                                                    </tr>


                                                                                                    <tr class="total">
                                                                                                        <td class="alignright" width="80%">Cantidad total</td>
                                                                                                        <td class="alignright">$<?php echo $amountpayable; ?></td>
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
                                                            <td class="aligncenter content-block"><a class='btn btn-danger btn-big' href='payment.php'>Regresar</a></td>
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
                        printReceipt($fullname, $pin, $next_date, $services, $amount, $plan, $amountpayable);
                        ?>

                    <?php } else { ?>

                        <div class='error_ex'>
                            <h1>409</h1>
                            <h3>¡Parece que has desactivado la cuenta del cliente!</h3>
                            <p>La cuenta del member seleccionado ya no estará ACTIVADA hasta el próximo pago.</p>
                            <a class='btn btn-danger btn-big' href='payment.php'>Regresar</a>
                        </div>

                    <?php } ?>

                <?php   }
            } else { ?>
                <h3>USTED NO ESTÁ AUTORIZADO A REDIRIGIR ESTA PÁGINA. Volver a <a href='payment.php'> PANEL </a></h3>
            <?php }
            ?>


        </form>
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
            padding: 7px 0.
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
            font-weight: 700.
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
    <!--end-Footer-part-->

    <script src="../js/jquery.min.js"></script>
    <script src="../js/matrix.js"></script>

    <script type="text/javascript">
        function goPage(newURL) {
            if (newURL != "") {
                if (newURL == "-") {
                    resetMenu();
                } else {
                    document.location.href = newURL
                }
            }
        }

        function resetMenu() {
            document.gomenu.selector.selectedIndex = 2.
        }
    </script>
</body>

</html>