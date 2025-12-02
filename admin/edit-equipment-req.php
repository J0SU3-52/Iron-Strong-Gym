<?php
  session_start();
    if(!isset($_SESSION['user_id'])) {
      header('location:../index.php');	
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Equipo de Gimnasio</title>
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
<?php include 'includes/topheader.php'?>

<?php $page='update-equip'; include 'includes/sidebar.php'?>


<!--sidebar-menu-->
<div id="content">
<div id="content-header">
  <div id="breadcrumb"> 
    <a href="index.html" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
    <a href="#" class="tip-bottom">Administrar Equipos</a> 
    <a href="#" class="current">Actualizar Equipos</a> </div>
  <h1>Actualizar Equipos</h1>
</div>
<form role="form" action="index.php" method="POST">
    <?php 

            if(isset($_POST['name'])){
            $name = $_POST["name"];    
            // $amount = $_POST["amount"];
            $vendor = $_POST["vendor"];
            $description = $_POST["description"];
            // $address = $_POST["address"];
            // $contact = $_POST["contact"];
            $date = $_POST["date"];
            $quantity = $_POST["quantity"];
            $id=$_POST['id'];

            // $totalamount = $amount * $quantity;
            
            include 'dbcon.php';
            //code after connection is successfull
            //update query
            $qry = "update equipment set name='$name',vendor='$vendor', description='$description', date='$date', quantity='$quantity' where id='$id'";
            $result = mysqli_query($con,$qry); //query executes

            if(!$result){
                echo"<div class='container-fluid'>";
                    echo"<div class='row-fluid'>";
                    echo"<div class='span12'>";
                    echo"<div class='widget-box'>";
                    echo"<div class='widget-title'> <span class='icon'> <i class='fas fa-info'></i> </span>";
                        echo"<h5>Error Message</h5>";
                        echo"</div>";
                        echo"<div class='widget-content'>";
                            echo"<div class='error_ex'>";
                            echo"<h1 style='color:maroon;'>Error 404</h1>";
                            echo"<h3>Error occured while updating your details</h3>";
                            echo"<p>Please Try Again</p>";
                            echo"<a class='btn btn-warning btn-big'  href='edit-equipment.php'>Regresar</a> </div>";
                        echo"</div>";
                        echo"</div>";
                    echo"</div>";
                    echo"</div>";
                echo"</div>";
            }else {

                echo"<div class='container-fluid'>";
                    echo"<div class='row-fluid'>";
                    echo"<div class='span12'>";
                    echo"<div class='widget-box'>";
                    echo"<div class='widget-title'> <span class='icon'> <i class='fas fa-info'></i> </span>";
                        echo"<h5>Message</h5>";
                        echo"</div>";
                        echo"<div class='widget-content'>";
                            echo"<div class='error_ex'>";
                            echo"<h1>Exito</h1>";
                            echo"<h3>¡Se han actualizado los detalles del equipo!</h3>";
                            echo"<p>Los datos solicitados están actualizados. Por favor haga clic en el botón para regresar.</p>";
                            echo"<a class='btn btn-inverse btn-big'  href='equipment.php'>Regresar</a> </div>";
                        echo"</div>";
                        echo"</div>";
                    echo"</div>";
                    echo"</div>";
                echo"</div>";
                // 
            }

            }else{
                echo"<h3>USTED NO ESTÁ AUTORIZADO A REDIRIGIR ESTA PÁGINA. Volver a <a href='index.php'>PANEL</a></h3>";
            }
?>
                                                               
                
             </form>
         </div>
</div></div>
</div>

<!--end-main-container-part-->

<!--Footer-part-->

<div class="row-fluid">
  <div id="footer" class="span12"> <?php echo date("Y");?> &copy; Developed By Josué and Lazaro</a> </div>
</div>

<style>
#footer {
  color: white;
}
</style>

<!--end-Footer-part-->

<script src="../js/jquery.min.js"></script>
<script src="../js/matrix.js"></script>

<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>
</body>
</html>
