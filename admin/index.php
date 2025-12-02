<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('location:../index.php');
}
include "dbcon.php";

// Consulta para obtener el número de asistencias diarias
$today = date('Y-m-d');
$attendance_query = "SELECT COUNT(*) as attendance_count FROM attendance WHERE DATE(attendance_date) = '$today'";
$attendance_result = mysqli_query($con, $attendance_query);
$attendance_data = mysqli_fetch_assoc($attendance_result);
$daily_attendance_count = $attendance_data['attendance_count'];

$qry = "SELECT services, count(*) as number FROM members GROUP BY services";
$result = mysqli_query($con, $qry);
$qry = "SELECT gender, count(*) as enumber FROM members GROUP BY gender";
$result3 = mysqli_query($con, $qry);

date_default_timezone_set('America/Mexico_City');
$current_date = date('Y-m-d h:i A');
$exp_date_time = explode(' ', $current_date);
$curr_date =  $exp_date_time['0'];
$curr_time =  $exp_date_time['1'] . ' ' . $exp_date_time['2'];

?>

<!DOCTYPE html>

<html lang="es">

<head>
  <title>Home</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />

  <link rel="stylesheet" href="../css/matrix-style.css" />
  <link rel="stylesheet" href="../css/matrix-media.css" />
  <link rel="stylesheet" href="../css/jquery.gritter.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js">
  </script>


  <!-- Función de Grafica "Informe de servicios" -->
  <script type="text/javascript">
    google.charts.load('current', {
      'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
      var data = new google.visualization.arrayToDataTable([
        ['Services', 'No. de Clientes'],
        <?php
        $query = "SELECT services, count(*) as number FROM members GROUP BY services";
        $res = mysqli_query($con, $query);
        while ($data = mysqli_fetch_array($res)) {
          $services = $data['services'];
          $number = $data['number'];
        ?>['<?php echo $services; ?>', <?php echo $number; ?>],
        <?php
        }
        ?>
      ]);

      var options = {
        width: 710,
        legend: {
          position: 'none'
        },
        bars: 'vertical',
        axes: {
          x: {
            0: {
              side: 'top',
              label: 'Total'
            }
          }
        },
        colors: ['E37722'],
        bar: {
          groupWidth: "100%"
        }
      };

      var chart = new google.charts.Bar(document.getElementById('top_x_div'));
      chart.draw(data, options);
    };
  </script>

  <!-- Función de Grafica "Informe de Ganancias Totales" -->
  <script type="text/javascript">
    google.charts.load('current', {
      'packages': ['bar']
    });

    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
      // Data for members
      const dataMembers = new google.visualization.arrayToDataTable([
        ['', 'Cantidad total'],
        <?php
        $query1 = "SELECT 'Clientes' as label, SUM(amount) as total_amount FROM members;";
        $result_members = mysqli_query($con, $query1);
        while ($data = mysqli_fetch_array($result_members)) {
          $label = $data['label'];
          $total_amount = $data['total_amount'];
        ?>['<?php echo $label; ?>', <?php echo $total_amount; ?>],
        <?php
        }
        ?>
      ]);

      const optionsMembers = {
        width: 550,
        height: 150,
        legend: {
          position: 'none'
        },
        bars: 'horizontal',
        axes: {
          x: {
            0: {
              side: 'top',
              label: 'Total'
            }
          }
        },
        bar: {
          groupWidth: "90%"
        },
        colors: ['005F37'],

        backgroundColor: '#f4f4f4',

        chartArea: {
          left: 50,
          top: 20,
          width: '85%',
          height: '65%'
        },
        tooltip: {
          isHtml: true
        }
      };

      const chartMembers = new google.charts.Bar(document.getElementById('members_div'));
      chartMembers.draw(dataMembers, optionsMembers);

      // Data for views
      const dataViews = new google.visualization.arrayToDataTable([
        ['', 'Cantidad total'],
        <?php
        $query2 = "SELECT 'Visitas' as label, SUM(amount) as total_views_amount FROM views;";
        $result_views = mysqli_query($con, $query2);
        while ($data = mysqli_fetch_array($result_views)) {
          $label = $data['label'];
          $total_views_amount = $data['total_views_amount'];
        ?>['<?php echo $label; ?>', <?php echo $total_views_amount; ?>],
        <?php
        }
        ?>
      ]);

      const optionsViews = {
        width: 600,
        height: 150,
        legend: {
          position: 'none'
        },
        bars: 'horizontal',
        axes: {
          x: {
            0: {
              side: 'top',
              label: 'Total'
            }
          }
        },
        bar: {
          groupWidth: "90%"
        },
        colors: ['D10023'],
        backgroundColor: '#f4f4f4',
        chartArea: {
          left: 50,
          top: 20,
          width: '85%',
          height: '65%'
        },
        tooltip: {
          isHtml: true
        }
      };

      const chartViews = new google.charts.Bar(document.getElementById('views_div'));
      chartViews.draw(dataViews, optionsViews);
    };
  </script>

  <!-- Función de Grafica "Estadísticas Diarias de Asistencia" -->
  <script type="text/javascript">
    google.charts.load('current', {
      'packages': ['corechart']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      const dataAttendance = new google.visualization.DataTable();
      dataAttendance.addColumn('string', 'Week');
      dataAttendance.addColumn('number', 'Total de Asistencia Semanal');

      <?php
      $query = "
            SELECT DATE_FORMAT(attendance_date, '%Y-%u') AS week, DATE_FORMAT(attendance_date, '%b') AS month, COUNT(*) AS total_attendance
            FROM attendance
            GROUP BY week, month
            ORDER BY week";
      $result = mysqli_query($con, $query);
      while ($data = mysqli_fetch_array($result)) {
        $week = $data['week'];
        $month = $data['month'];
        $total_attendance = $data['total_attendance'];
      ?>
        dataAttendance.addRow(['<?php echo $month . ' ' . $week; ?>', <?php echo $total_attendance; ?>]);
      <?php
      }
      ?>

      const optionsAttendance = {
        title: 'Estadísticas Semanales de Asistencia',
        curveType: 'function',
        legend: {
          position: 'bottom'
        },
        hAxis: {
          title: 'Semana',
          titleTextStyle: {
            color: '#333'
          }
        },
        vAxis: {
          title: 'Total de Asistencias S E M A N A L',
          minValue: 0
        },
        colors: ['#1b9e77'],
        pointSize: 5,
        lineWidth: 3,
        chartArea: {
          left: 50,
          top: 50,
          width: '80%',
          height: '70%'
        },
        backgroundColor: '#f4f4f4',
        annotations: {
          textStyle: {
            fontSize: 12,
            bold: true,
            italic: true,
            color: '#871b47',
            auraColor: '#d799ae',
            opacity: 0.8
          }
        }
      };

      const attendanceChart = new google.visualization.LineChart(document.getElementById('attendance_chart_div'));
      attendanceChart.draw(dataAttendance, optionsAttendance);
    };
  </script>


  <!-- Función de Grafica "Estadísticas Mensuales de Visitas" -->
  <script type="text/javascript">
    google.charts.load('current', {
      'packages': ['corechart']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      const dataLine = new google.visualization.DataTable();
      dataLine.addColumn('string', 'Month');
      dataLine.addColumn('number', 'Total Visitas');

      <?php
      $query = "
            SELECT DATE_FORMAT(view_date, '%Y-%m') AS month, COUNT(*) AS total_visits
            FROM views
            GROUP BY month
            ORDER BY month";
      $result = mysqli_query($con, $query);
      while ($data = mysqli_fetch_array($result)) {
        $month = $data['month'];
        $total_visits = $data['total_visits'];
      ?>
        dataLine.addRow(['<?php echo $month; ?>', <?php echo $total_visits; ?>]);
      <?php
      }
      ?>

      const optionsLine = {
        title: 'Estadísticas Mensuales de Visitas',
        curveType: 'function',
        legend: {
          position: 'bottom'
        },
        hAxis: {
          title: 'Mes',
          titleTextStyle: {
            color: '#333'
          }
        },
        vAxis: {
          title: 'Total de V I S I T A S',
          minValue: 0
        },
        colors: ['007C33'],
        pointSize: 5,
        lineWidth: 3,
        chartArea: {
          left: 50,
          top: 50,
          width: '80%',
          height: '70%'
        },
        backgroundColor: '#f4f4f4',
        annotations: {
          textStyle: {
            fontSize: 12,
            bold: true,
            italic: true,
            color: '#871b47',
            auraColor: '#d799ae',
            opacity: 0.8
          }
        }
      };

      const lineChart = new google.visualization.LineChart(document.getElementById('line_chart_div'));
      lineChart.draw(dataLine, optionsLine);
    };
  </script>

  <!-- Función de Grafica "Clientes Registrados por Género: Descripción General" -->
  <script type="text/javascript">
    google.charts.load("current", {
      packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      const data = google.visualization.arrayToDataTable([
        ['Gender', 'Number'],
        <?php
        while ($row = mysqli_fetch_array($result3)) {
          echo "['" . $row["gender"] . "', " . $row["enumber"] . "],";
        }
        ?>
      ]);

      const options = {
        pieHole: 0.4,
      };

      const chart = new google.visualization.PieChart(document.getElementById('donutchart'));
      chart.draw(data, options);
    }
  </script>




</head>

<body>


  <div id="header">
    <h1><a href="dashboard.html"></a></h1>
  </div>

  <?php include 'includes/topheader.php' ?>

  <?php $page = 'dashboard';
  include 'includes/sidebar.php' ?>

  <div id="content">
    <div id="content-header">
    </div>

    <div class="container-fluid">
      <div class="quick-actions_homepage">
        <div id="content-header">
          <ul class="quick-actions">
            <li class="bg_ls span3"> <a href="member-status.php" style="font-size: 16px;"> <i class="fas fa-user-check"></i> <span class="label label-success"><?php include 'actions/dashboard-activecount.php' ?></span> Clientes Activos </a> </li>
            <li class="bg_lo span3"> <a href="clients.php" style="font-size: 16px;"> <i class="fas fa-users"></i><span class="label label-inverse"><?php include 'dashboard-usercount.php' ?></span> Clientes Registrados</a> </li>
            <li class="bg_lg span3"> <a href="payment.php" style="font-size: 16px;"> <i class="fa fa-dollar-sign"></i> Ganancias Totales: $<?php include '../admin/actions/income-count.php' ?></a> </li>
            <li class="bg_lv span3"> <a href="routine.php" style="font-size: 16px;"> <i class="fas fa-dumbbell"></i> <span class="label label-inverse"><?php include '../admin/actions/dashboard-routine-count.php' ?></span> Rutinas Actuales </a> </li>
            <li class="bg_ly span3"> <a href="clients-entry.php" style="font-size: 16px;"> <i class="fa-solid fa-user-plus"></i> Registrar Nuevo Cliente</a> </li>
            <li class="bg_lb span3"> <a href="#" style="font-size: 16px;"> <i class="fas fa-calendar-check"></i> Asistencias Diarias: <?php echo $daily_attendance_count; ?> - <?php echo date('d, M Y'); ?></a> </li>


          </ul>
        </div>
      </div>


      <div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title bg_lg"><span class="icon"><i class="fas fa-file"></i></span>
            <h5>Informe de servicios</h5>
          </div>
          <div class="widget-content">


            <!-- HTML Grafica de 'Informe de servicios' -->
            <div class="row-fluid">
              <div class="span8">
                <div id="top_x_div" style="width: 700px; height: 290px;"></div>
              </div>
              <div class="span4">
                <ul class="site-stats">
                  <!-- <li class="bg_lo"><i class="fas fa-users"></i> <strong><?php include 'dashboard-usercount.php'; ?></strong> <small>Total de miembros</small></li> -->
                  <li class="bg_lr"><i class="fas fa-dumbbell"></i> <strong><?php include 'actions/count-equipments.php'; ?></strong> <small>Equipos disponibles</small></li>
                  <!-- <li class="bg_lv"><i class="fas fa-dumbbell"></i> <strong><?php include 'actions/dashboard-routine-count.php'; ?></strong> <small>Rutinas totales</small></li> -->
                  <li class="bg_ls"><i class="fas fa-calendar-check"></i> <strong><?php include '../admin/actions/dashboard-views-count.php'; ?></strong> <small>Visitas Totales</small></li>
                </ul>
              </div>
            </div>


          </div>
        </div>
      </div>

      <!-- HTML Grafica de 'Informe de Ganancias Totales' -->
      <div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title bg_lg">
            <span class="icon"><i class="fas fa-file"></i></span>
            <h5>Informe de Ganancias Totales</h5>
          </div>
          <div class="widget-content">
            <div class="row-fluid">
              <div class="span12">
                <div id="members_div" style="width: 550px; height: 150px;"></div>
              </div>
              <div class="span12">
                <div id="views_div" style="width: 600px; height: 150px;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- HTML Grafica de 'Estadísticas Diarias de Asistencia' -->
      <div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title bg_lg">
            <span class="icon"><i class="fas fa-file"></i></span>
            <h5>Estadísticas Semanales de Asistencia</h5>
          </div>
          <div class="widget-content">
            <div class="row-fluid">
              <div class="span12">
                <div id="attendance_chart_div" style="width: 100%; height: 400px;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- HTML Grafica de 'Estadísticas Mensuales de Visitas' -->
      <div class="row-fluid">
        <div class="widget-box">
          <div class="widget-title bg_lg">
            <span class="icon"><i class="fas fa-file"></i></span>
            <h5>Estadísticas Mensuales de Visitas</h5>
          </div>
          <div class="widget-content">
            <div class="row-fluid">
              <div class="span12">
                <div id="line_chart_div" style="width: 100%; height: 400px;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- HTML Grafica de 'Clientes Registrados por Género: Descripción General' -->
      <div class="row-fluid">
        <div class="span6">
          <div class="widget-box">
            <div class="widget-title bg_ly" data-toggle="collapse" href="#collapseG2">
              <span class="icon"><i class="fas fa-chevron-down"></i></span>
              <h5>Clientes Registrados por Género: Descripción General</h5>
            </div>
            <div class="widget-content nopadding collapse in" id="collapseG2">
              <ul class="recent-posts">
                <div id="donutchart" style="width: 600px; height: 300px;"></div>
              </ul>
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

    #piechart {
      width: 800px;
      height: 280px;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
  <script src="../js/jquery.min.js"></script>
  <script src="../js/matrix.js"></script>
</body>

</html>