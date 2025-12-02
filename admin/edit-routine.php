<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
    exit();
}

include 'actions/dbcon.php';

$routine = null;
if (isset($_GET['id'])) {
    $routine_id = mysqli_real_escape_string($con, $_GET['id']);
    $qry = "SELECT * FROM routines WHERE routine_id = ?";
    $stmt = $con->prepare($qry);
    $stmt->bind_param("i", $routine_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $routine = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "No se proporcionó el ID de la rutina.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Editar Rutina Personalizadas</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="../css/matrix-style.css" />
    <link rel="stylesheet" href="../css/matrix-media.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/edit-routine.css"> <!-- Nuevo archivo CSS -->
    <!-- Aquí incluye jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
</head>

<body>
    <div id="header">
        <h1><a href="dashboard.html"></a></h1>
    </div>

    <?php include 'includes/topheader.php'; ?>
    <?php $page = 'create-routines';
    include 'includes/sidebar.php'; ?>

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb">
                <a href="index.php" title="ir a inicio" class="tip-bottom"><i class="fas fa-home"></i> Home</a>
                <a href="update-routine.php" class="tip-bottom">Actualizar Rutinas</a>
                <a href="#" class="current"><?php echo isset($routine) ? 'Editar Rutina Perzonalizada' : 'Crear rutina'; ?></a>
            </div>
            <h1 class="text-center"><?php echo isset($routine) ? 'Editar Rutina Perzonalizada' : 'Crear rutina'; ?> <i class="fas fa-dumbbell"></i></h1>
        </div>


        <div class="container-fluid">
            <form id="routineForm" action="routine-handler.php" method="POST" class="form-horizontal custom-form" onsubmit="updateHiddenInput()">
                <?php if (isset($routine)) { ?>
                    <input type="hidden" name="routine_id" value="<?php echo $routine['routine_id']; ?>">
                <?php } ?>


                <div class="row">
                    <div class="col-md-6">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"> <i class="fas fa-align-justify"></i> </span>
                                <h5>Información personal</h5>
                            </div>
                            <div class="widget-content">
                                <div class="form-container">
                                    <div class="form-row">
                                        <div class="control-group">
                                            <label class="control-label">Nombre Completo:</label>
                                            <div class="controls">
                                                <input type="text" class="span3" name="fullname" value="<?php echo isset($routine) ? htmlspecialchars($routine['fullname']) : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label class="control-label">Nombre de la rutina:</label>
                                            <div class="controls">
                                                <input type="text" class="span3" name="routine_name" value="<?php echo isset($routine) ? htmlspecialchars($routine['routine_name']) : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="control-group full-width">
                                            <label class="control-label">Correo electrónico:</label>
                                            <div class="controls">
                                                <input type="email" class="span3" name="gmail" value="<?php echo isset($routine) ? htmlspecialchars($routine['gmail']) : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon"> <i class="fas fa-align-justify"></i> </span>
                                <h5>Detalles de servicio</h5>
                            </div>
                            <div class="widget-content">
                                <div class="form-container">
                                    <div class="form-row">
                                        <div class="control-group">
                                            <label class="control-label">Fecha :</label>
                                            <div class="controls">
                                                <input type="date" name="dor" class="span3" value="<?php echo isset($routine) ? htmlspecialchars($routine['dor']) : ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="control-group">
                                            <label class="control-label">Servicios</label>
                                            <div class="controls">
                                                <label class="radio-label"><input type="radio" value="Normal" name="services" <?php if (isset($routine) && $routine['services'] == 'Normal') echo 'checked'; ?> required /> Normal <small>- $400 por mes</small></label>
                                                <label class="radio-label"><input type="radio" value="Estudiante" name="services" <?php if (isset($routine) && $routine['services'] == 'Estudiante') echo 'checked'; ?> required /> Estudiante <small>- $350 por mes</small></label>
                                                <label class="radio-label"><input type="radio" value="Cardio" name="services" <?php if (isset($routine) && $routine['services'] == 'Cardio') echo 'checked'; ?> required /> Cardio <small>- $250 por mes</small></label>
                                                <label class="radio-label"><input type="radio" value="Normal + Cardio" name="services" <?php if (isset($routine) && $routine['services'] == 'Normal + Cardio') echo 'checked'; ?> required /> Normal + Cardio <small>- $500 por mes</small></label>
                                                <label class="radio-label"><input type="radio" value="Estudiante + Cardio" name="services" <?php if (isset($routine) && $routine['services'] == 'Estudiante + Cardio') echo 'checked'; ?> required /> Estudiante + Cardio<small>- $400 por mes</small></label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="ejerciciosSeleccionados" id="ejerciciosSeleccionados" value='<?php echo isset($routine) ? htmlspecialchars($routine['ejerciciosSeleccionados']) : '[]'; ?>'>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success"><?php echo isset($routine) ? 'Actualizar' : 'Crear'; ?></button>
                                        <button type="button" class="btn btn-primary" onclick="mostrarPrevisualizacion()">Mostrar Previsualización</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <!--EJERCICIOS LUNES -->
                <div class="day-container">
                    <div class="day-title">Lunes</div>

                    <h4>Tren superior</h4>

                    <div class="control-group full-width">
                        <label class="control-label">Título</label>
                        <div class="controls">
                            <input type="text" name="title_routineday_Lunes" class="span3" value="<?php echo isset($routine) ? htmlspecialchars($routine['title_routineday_Lunes']) : ''; ?>" />
                        </div>
                    </div>

                    <div class="exercises">


                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Press de Pecho en Máquina Precor 4x12', this)">
                            Press de Pecho en Máquina Precor 4x12
                            <img src="https://www.precorhomefitness.com/cdn/shop/files/precor-vitality-series-chest-press-c001-475246.jpg?v=1712890834" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Press de Pecho Declinado 4x12', this)">
                            Press de Pecho Declinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/decline-bench-press/decline-bench-press-800.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Press de Pecho Plano 4x12', this)">
                            Press de Pecho Plano 4x12
                            <img src="https://i.blogs.es/0fdc2d/bench_press/450_1000.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Press de Pecho Inclinado 4x12', this)">
                            Press de Pecho Inclinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/incline-bench-press/incline-bench-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Press de Pecho Inclinado en Máquina 4x12', this)">
                            Press de Pecho Inclinado en Máquina 4x12
                            <img src="https://www.equipamientosfox.com/wp-content/uploads/2022/03/linea-full_lingotes_press-de-pecho-inclinado.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Press inclinado en Máquina Hammer 4x12', this)">
                            Press inclinado en Máquina Hammer 4x12
                            <img src="https://www.ukgymequipment.com/images/plate-loaded-iso-lateral-decline-chest-press-p1828-18873_image.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Pec Fly en Máquina 4x12', this)">
                            Pec Fly en Máquina 4x12
                            <img src="https://i.imgur.com/3QJ0nVg.jpeg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Pec Deck', this)">
                            Pec Deck 4x12
                            <img src="https://www.shutterstock.com/image-vector/man-doing-machine-bent-arm-600nw-2379165459.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Pet Fly en Máquina 4x12', this)">
                            Pet Fly en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7N7aQwXqt4Efy44U4gm8i9QRIpyzCXqplFgo67YVIHPIleAU2JUSsp9kBAsc_YIvGG_I&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Cross-Over Pecho 4x12', this)">
                            Cross-Over Pecho 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/cable-fly/cable-fly-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Remo en Máquina 4x12', this)">
                            Remo en Máquina 4x12
                            <img src="https://i.imgur.com/DZ0bjZi.jpeg" alt="Incline press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Remo con agarre en V 4x12', this)">
                            Remo con agarre en V 4x12
                            <img src="https://static.gym-training.com/upload/image/original/5e/05/5e053461b689209c7baaa60d4e97cc74.jpeg?ae17ef5dda5afeaaabf0214fa57e32c0" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Remo con Agarre Abierto 4x12', this)">
                            Remo con Agarre Abierto 4x12
                            <img src="https://s3assets.skimble.com/assets/1874224/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Remo T en Máquina 4x12', this)">
                            Remo T en Máquina 4x12
                            <img src="https://cdn.globalso.com/dhzfitness/Incline-Level-Row-U2061-2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Jalon en polea alta Espalda 4x12', this)">
                            Jalon en polea alta Espalda 4x12
                            <img src="https://s3assets.skimble.com/assets/2179480/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Remo Articulado en Máquina 4x12', this)">
                            Remo Articulado en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKMRDeNFa5SdOAzmgnxHSnkFmMD6W9MPq1G2pRCiMyvjvh007ap_lSdvsEbG9fp9vhi4k&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Remo alto en Máquina Hammer 4x12', this)">
                            Remo alto en Máquina Hammer 4x12
                            <img src="https://gfitness.fi/storage/attachments/nn6/bfp/dw1/nn6bfpdw19czxlavb29f26x2-800x_-resize.jpg?hammer-strength-iso-lateral-low-row" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Pull Down en Máquina 4x12', this)">
                            Pull Down en Máquina 4x12
                            <img src="https://musclemagfitness.com/wp-content/uploads/reverse-grip-lat-pulldown-exercise-300x300.jpg.webp" alt="Standing overhead press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Delt Fly en Máquina 4x12', this)">
                            Delt Fly en Máquina 4x12
                            <img src="https://s3assets.skimble.com/assets/1852429/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Curl de Biceps en Máquina Hammer 4x12', this)">
                            Curl de Biceps en Máquina Hammer 4x12
                            <img src="https://www.pinnaclefitness.org.uk/wp-content/uploads/2023/11/141-1.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Curl de Biceps en Maquina Cybex 4x1  ', this)">
                            Curl de Biceps en Maquina Cybex 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRk0u3UNyubTt_oVcVnSNlxfCe03UOjMcZ9YQ&s" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Fondos en Máquina 4x12', this)">
                            Fondos en Máquina 4x12
                            <img src="https://i5.walmartimages.com/asr/ac9dabc5-8127-4c22-8117-9c48e3cce196_1.d4a65f8056364a7a425314f016eab52f.jpeg?odnHeight=768&odnWidth=768&odnBg=FFFFFF" alt="Pull-ups">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Extencion de Triceps en Maquina', this)">
                            Extencion de Triceps en Máquina 4x12
                            <img src="https://s3.amazonaws.com/prod.skimble/assets/1871648/image_iphone.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Rack de Hombro Hammer', this)">
                            Press Militar con Barra 4 x 12
                            <img src="https://www.deportrainer.com/img/cms/Post%20de%20blog/ejercicios_hombro/ejercicio-press-militar-con-barra-sentado.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Press de Hombro en Maquina 4x12', this)">
                            Press de Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-shoulder-press/machine-shoulder-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Deltoides para Hombro en Máquina 4x12', this)">
                            Deltoides para Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-lateral-raise/machine-lateral-raise-800.jpg" alt="Chest supported row">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Hombro Lateral en Máquina 4x12', this)">
                            Hombro Lateral en Máquina 4x12
                            <img src="https://www.fullcirclepadding.com/assets/1/26/DimThumbnail/4065-Shoulder-Internal-External-Rotation-CYW102.jpg?34880" alt="Incline overhead extension">
                        </button>

                    </div>

                    <!--TREN INFERIOR -->
                    <h3>Tren inferior</h3>

                    <div class="exercises">

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Banco para Hipertensiones Espalda Baja', this)">
                            Banco para Hipertensiones Espalda Baja 4x12
                            <img src="https://www.ultrafitness.mx/412-home_default/banca-para-hipertensiones-espalda-baja.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Peso Muerto en Máquina', this)">
                            Peso Muerto en Maquina 4x12
                            <img src="https://lh5.googleusercontent.com/proxy/JBBLBQ6XanxRnc5NlX0tfxjagyh2_sY19NLPv7vJkw3lW99zMran8XJVKnhXUxb0vOHCVNA8VmBliQkkzPONq58" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Sentadilla Barra Libre 4x12', this)">
                            Sentadilla Barra Libre 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/squat/squat-800.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Hack Squat 4x12', this)">
                            Hack Squat 4x12
                            <img src="https://samsfitness.com.au/wp-content/uploads/2022/11/atx-bpr-790-hack-squatting.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Femoral Sentado en Máquina 4x12', this)">
                            Femoral Sentado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/seated-leg-curl/seated-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Curl Femoral de Pie 4x12', this)">
                            Curl Femoral de Pie 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/standing-leg-curl/standing-leg-curl-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Hack Horizontal en Máquina 4x12', this)">
                            Hack Horizontal en Máquina 4x12
                            <img src="https://www.lyfta.app/_next/image?url=%2Fthumbnails%2F07441201.jpg&w=3840&q=20" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Prensa Sentado 4x12', this)">
                            Prensa Sentado 4x12
                            <img src="https://cdn4.volusion.store/aqyor-dadrn/v/vspfiles/photos/LFPro1slp-2.jpg?v-cache=1719387531" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Extension de Cuadricep en Máquina 4x12', this)">
                            Extension de Cuadricep en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/leg-extension/leg-extension-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Femoral Acostado en Máquina 4x12', this)">
                            Femoral Acostado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/lying-leg-curl/lying-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Pantorilla Inclinado en Máquina 4x12', this)">
                            Pantorrilla Inclinado en Máquina
                            <img src="https://www.globalfitness.com/cdn/shop/products/precor-icarian-angled-seated-calf_fd638229-3d71-491a-82a3-152bf8748515.jpg?v=1548436124" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Multi Hip para Glúteo 4x12', this)">
                            Multi Hip para Glúteo 4x12
                            <img src="https://www.profitnessmx.com/image/cache/catalog/Inflight-Fitness/inflight-fitness-multi-hip-glute-2-500x500.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Patada Femoral-Glúteo 4x12', this)">
                            Patada Femoral-Glúteo 4x12
                            <img src="https://tkstar.com/cdn/shop/products/Precor-Glute-618-2.png?v=1695909425" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Press en Máquina Horizontal', this)">
                            Press en Máquina Horizontal 4x12
                            <img src="https://images.offerup.com/37qPierSIR9ELhS0tU-xsUuSeWA=/1440x1920/07ad/07adc89c342345d7a355bd9e111dda1e.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Hip Abductor 4x12', this)">
                            Hip Abductor 4x12
                            <img src="https://i.pinimg.com/originals/59/83/a8/5983a8508eaf3445277b95bf13efc6a6.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Adductor 4x12', this)">
                            Adductor 4x12
                            <img src="https://static.strengthlevel.com/images/illustrations/hip-adduction-1000x1000.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Leg Press en Hammer 4x12', this)">
                            Leg Press en Hammer 4x12
                            <img src="https://www.fitnessforlifemx.com/cdn/shop/products/PlateLoadedLegPress_1200x.png?v=1680795665" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Prensa a 45° 4x12', this)">
                            Prensa a 45° 4x12
                            <img src="https://guiafitness.com/wp-content/uploads/20080731psaeef_1_ies_lco.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Sentadilla Perfecta 4x12', this)">
                            Sentadilla Perfecta 4x12
                            <img src="https://www.ultrafitness.mx/29-large_default/aparato-para-sentadilla-y-pantorrilla-parado-body-solid-gscl360.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Costurera Patorrilla 4x12', this)">
                            Costurera Patorrilla 4x12
                            <img src="https://importfitness.com/wp-content/uploads/2023/10/WhatsApp-Image-2023-05-30-at-12.42.55-PM-51.jpeg.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Prensa Pendulo Articulada 4x12', this)">
                            Prensa Pendulo Articulada 4x12
                            <img src="https://http2.mlstatic.com/D_NQ_NP_693194-MLM51995845620_102022-O.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Sentadilla 3D 4x12', this)">
                            Sentadilla 3D 4x12
                            <img src="https://image.made-in-china.com/2f0j00CjTEtZGMRQpz/Top-Quality-Hoist-Fitness-Equipment-Squat-Smith-SR1-32-.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Sentadilla Smith 4x12', this)">
                            Sentadilla Smith 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/smith-machine-squat/smith-machine-squat-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Hip Thrust con Barra 4x12', this)">
                            Hip Thrust con Barra 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/hip-thrust/hip-thrust-800.jpg" alt="Peso muerto">
                        </button>

                    </div>

                    <h3>Cardio</h3>

                    <div class="exercises">

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Eliptica', this)">
                            Eliptica
                            <img src="https://caminadorasygimnasios.com/cdn/shop/products/43619794823_1_1200x1200.jpg?v=1660063333" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Caminadora', this)">
                            Caminadora
                            <img src="https://www.alphafitness.mx/wp-content/uploads/2021/04/Star-trac-etrx-led.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Bicicleta', this)">
                            Bicicleta
                            <img src="https://www.mercadazo.com.mx/115595-large_default/bicicleta-fija-ergo-bike-bimex-con-medidor-de-funciones.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Crunches en Maquina', this)">
                            Crunches en Maquina
                            <img src="https://static.strengthlevel.com/images/exercises/machine-seated-crunch/machine-seated-crunch-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Espalda Baja en Máquina', this)">
                            Espalda Baja en Máquina
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2016/10/S2ABB_Ex_F_2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Abdominales en Banco Abdominal 4x12', this)">
                            Abdominales en Banco Abdominal 4x12
                            <img src="https://contents.mediadecathlon.com/m10766761/k$6bdf2c6869bf06a99ca9a62e5aabd7b1/sq/250x250/Banco-De-Musculacion-Para-Abdominales-Vital-Gym-ES-507.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Abdominales en Banco de Crunches 4x12', this)">
                            Abdominales en Banco de Crunches 4x12
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2020/07/BFHYP10r_Hero-Folded_DSF2125-2000.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Lunes', 'Abdominales en Banco de Flexor 4x12', this)">
                            Abdominales en Banco de Flexor 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYkIjc3YUc1sRkYLDZJtQaEJ0DZUkE1sgP0Q&s" alt="image">
                        </button>

                    </div>

                    <div class="selected-exercises" id="selected-exercises-Lunes" style="display: none;">
                        <ul></ul>
                    </div>
                </div>

                <!-- EJERCICIOS MARTES -->
                <div class="day-container">
                    <div class="day-title">Martes</div>

                    <h6>Tren superior</h6>

                    <div class="control-group full-width">
                        <label class="control-label">Título</label>
                        <div class="controls">
                            <input type="text" name="title_routineday_Tuesday" class="span3" value="<?php echo isset($routine) ? htmlspecialchars($routine['title_routineday_Tuesday']) : ''; ?>" />
                        </div>
                    </div>

                    <div class="exercises">


                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Press de Pecho en Máquina Precor 4x12', this)">
                            Press de Pecho en Máquina Precor 4x12
                            <img src="https://www.precorhomefitness.com/cdn/shop/files/precor-vitality-series-chest-press-c001-475246.jpg?v=1712890834" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Press de Pecho Declinado 4x12', this)">
                            Press de Pecho Declinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/decline-bench-press/decline-bench-press-800.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Press de Pecho Plano 4x12', this)">
                            Press de Pecho Plano 4x12
                            <img src="https://i.blogs.es/0fdc2d/bench_press/450_1000.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Press de Pecho Inclinado 4x12', this)">
                            Press de Pecho Inclinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/incline-bench-press/incline-bench-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Press de Pecho Inclinado en Máquina 4x12', this)">
                            Press de Pecho Inclinado en Máquina 4x12
                            <img src="https://www.equipamientosfox.com/wp-content/uploads/2022/03/linea-full_lingotes_press-de-pecho-inclinado.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Press inclinado en Máquina Hammer 4x12', this)">
                            Press inclinado en Máquina Hammer 4x12
                            <img src="https://www.ukgymequipment.com/images/plate-loaded-iso-lateral-decline-chest-press-p1828-18873_image.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Pec Fly en Máquina 4x12', this)">
                            Pec Fly en Máquina 4x12
                            <img src="https://i.imgur.com/3QJ0nVg.jpeg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Pec Deck', this)">
                            Pec Deck 4x12
                            <img src="https://www.shutterstock.com/image-vector/man-doing-machine-bent-arm-600nw-2379165459.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Pet Fly en Máquina 4x12', this)">
                            Pet Fly en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7N7aQwXqt4Efy44U4gm8i9QRIpyzCXqplFgo67YVIHPIleAU2JUSsp9kBAsc_YIvGG_I&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Cross-Over Pecho 4x12', this)">
                            Cross-Over Pecho 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/cable-fly/cable-fly-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Remo en Máquina 4x12', this)">
                            Remo en Máquina 4x12
                            <img src="https://i.imgur.com/DZ0bjZi.jpeg" alt="Incline press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Remo con agarre en V 4x12', this)">
                            Remo con agarre en V 4x12
                            <img src="https://static.gym-training.com/upload/image/original/5e/05/5e053461b689209c7baaa60d4e97cc74.jpeg?ae17ef5dda5afeaaabf0214fa57e32c0" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Remo con Agarre Abierto 4x12', this)">
                            Remo con Agarre Abierto 4x12
                            <img src="https://s3assets.skimble.com/assets/1874224/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Remo T en Máquina 4x12', this)">
                            Remo T en Máquina 4x12
                            <img src="https://cdn.globalso.com/dhzfitness/Incline-Level-Row-U2061-2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Jalon en polea alta Espalda 4x12', this)">
                            Jalon en polea alta Espalda 4x12
                            <img src="https://s3assets.skimble.com/assets/2179480/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Remo Articulado en Máquina 4x12', this)">
                            Remo Articulado en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKMRDeNFa5SdOAzmgnxHSnkFmMD6W9MPq1G2pRCiMyvjvh007ap_lSdvsEbG9fp9vhi4k&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Remo alto en Máquina Hammer 4x12', this)">
                            Remo alto en Máquina Hammer 4x12
                            <img src="https://gfitness.fi/storage/attachments/nn6/bfp/dw1/nn6bfpdw19czxlavb29f26x2-800x_-resize.jpg?hammer-strength-iso-lateral-low-row" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Pull Down en Máquina 4x12', this)">
                            Pull Down en Máquina 4x12
                            <img src="https://musclemagfitness.com/wp-content/uploads/reverse-grip-lat-pulldown-exercise-300x300.jpg.webp" alt="Standing overhead press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Delt Fly en Máquina 4x12', this)">
                            Delt Fly en Máquina 4x12
                            <img src="https://s3assets.skimble.com/assets/1852429/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Curl de Biceps en Máquina Hammer 4x12', this)">
                            Curl de Biceps en Máquina Hammer 4x12
                            <img src="https://www.pinnaclefitness.org.uk/wp-content/uploads/2023/11/141-1.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Curl de Biceps en Maquina Cybex 4x1  ', this)">
                            Curl de Biceps en Maquina Cybex 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRk0u3UNyubTt_oVcVnSNlxfCe03UOjMcZ9YQ&s" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Fondos en Máquina 4x12', this)">
                            Fondos en Máquina 4x12
                            <img src="https://i5.walmartimages.com/asr/ac9dabc5-8127-4c22-8117-9c48e3cce196_1.d4a65f8056364a7a425314f016eab52f.jpeg?odnHeight=768&odnWidth=768&odnBg=FFFFFF" alt="Pull-ups">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Extencion de Triceps en Maquina', this)">
                            Extencion de Triceps en Máquina 4x12
                            <img src="https://s3.amazonaws.com/prod.skimble/assets/1871648/image_iphone.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Rack de Hombro Hammer', this)">
                            Press Militar con Barra 4 x 12
                            <img src="https://www.deportrainer.com/img/cms/Post%20de%20blog/ejercicios_hombro/ejercicio-press-militar-con-barra-sentado.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Press de Hombro en Maquina 4x12', this)">
                            Press de Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-shoulder-press/machine-shoulder-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Deltoides para Hombro en Máquina 4x12', this)">
                            Deltoides para Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-lateral-raise/machine-lateral-raise-800.jpg" alt="Chest supported row">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Hombro Lateral en Máquina 4x12', this)">
                            Hombro Lateral en Máquina 4x12
                            <img src="https://www.fullcirclepadding.com/assets/1/26/DimThumbnail/4065-Shoulder-Internal-External-Rotation-CYW102.jpg?34880" alt="Incline overhead extension">
                        </button>

                    </div>

                    <!--TREN INFERIOR -->
                    <h6>Tren inferior</h6>

                    <div class="exercises">

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Banco para Hipertensiones Espalda Baja', this)">
                            Banco para Hipertensiones Espalda Baja 4x12
                            <img src="https://www.ultrafitness.mx/412-home_default/banca-para-hipertensiones-espalda-baja.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Peso Muerto en Máquina', this)">
                            Peso Muerto en Máquina 4x12
                            <img src="https://lh5.googleusercontent.com/proxy/JBBLBQ6XanxRnc5NlX0tfxjagyh2_sY19NLPv7vJkw3lW99zMran8XJVKnhXUxb0vOHCVNA8VmBliQkkzPONq58" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Sentadilla Barra Libre 4x12', this)">
                            Sentadilla Barra Libre 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/squat/squat-800.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Hack Squat 4x12', this)">
                            Hack Squat 4x12
                            <img src="https://samsfitness.com.au/wp-content/uploads/2022/11/atx-bpr-790-hack-squatting.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Femoral Sentado en Máquina 4x12', this)">
                            Femoral Sentado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/seated-leg-curl/seated-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Curl Femoral de Pie 4x12', this)">
                            Curl Femoral de Pie 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/standing-leg-curl/standing-leg-curl-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Hack Horizontal en Máquina 4x12', this)">
                            Hack Horizontal en Máquina 4x12
                            <img src="https://www.lyfta.app/_next/image?url=%2Fthumbnails%2F07441201.jpg&w=3840&q=20" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Prensa Sentado 4x12', this)">
                            Prensa Sentado 4x12
                            <img src="https://cdn4.volusion.store/aqyor-dadrn/v/vspfiles/photos/LFPro1slp-2.jpg?v-cache=1719387531" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Extension de Cuadricep en Máquina 4x12', this)">
                            Extension de Cuadricep en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/leg-extension/leg-extension-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Femoral Acostado en Máquina 4x12', this)">
                            Femoral Acostado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/lying-leg-curl/lying-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Pantorilla Inclinado en Máquina 4x12', this)">
                            Pantorrilla Inclinado en Máquina
                            <img src="https://www.globalfitness.com/cdn/shop/products/precor-icarian-angled-seated-calf_fd638229-3d71-491a-82a3-152bf8748515.jpg?v=1548436124" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Multi Hip para Glúteo 4x12', this)">
                            Multi Hip para Glúteo 4x12
                            <img src="https://www.profitnessmx.com/image/cache/catalog/Inflight-Fitness/inflight-fitness-multi-hip-glute-2-500x500.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Patada Femoral-Glúteo 4x12', this)">
                            Patada Femoral-Glúteo 4x12
                            <img src="https://tkstar.com/cdn/shop/products/Precor-Glute-618-2.png?v=1695909425" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Press en Máquina Horizontal', this)">
                            Press en Máquina Horizontal 4x12
                            <img src="https://images.offerup.com/37qPierSIR9ELhS0tU-xsUuSeWA=/1440x1920/07ad/07adc89c342345d7a355bd9e111dda1e.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Hip Abductor 4x12', this)">
                            Hip Abductor 4x12
                            <img src="https://i.pinimg.com/originals/59/83/a8/5983a8508eaf3445277b95bf13efc6a6.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Adductor 4x12', this)">
                            Adductor 4x12
                            <img src="https://static.strengthlevel.com/images/illustrations/hip-adduction-1000x1000.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Leg Press en Hammer 4x12', this)">
                            Leg Press en Hammer 4x12
                            <img src="https://www.fitnessforlifemx.com/cdn/shop/products/PlateLoadedLegPress_1200x.png?v=1680795665" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Prensa a 45° 4x12', this)">
                            Prensa a 45° 4x12
                            <img src="https://guiafitness.com/wp-content/uploads/20080731psaeef_1_ies_lco.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Sentadilla Perfecta 4x12', this)">
                            Sentadilla Perfecta 4x12
                            <img src="https://www.ultrafitness.mx/29-large_default/aparato-para-sentadilla-y-pantorrilla-parado-body-solid-gscl360.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Costurera Patorrilla 4x12', this)">
                            Costurera Patorrilla 4x12
                            <img src="https://importfitness.com/wp-content/uploads/2023/10/WhatsApp-Image-2023-05-30-at-12.42.55-PM-51.jpeg.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Prensa Pendulo Articulada 4x12', this)">
                            Prensa Pendulo Articulada 4x12
                            <img src="https://http2.mlstatic.com/D_NQ_NP_693194-MLM51995845620_102022-O.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Sentadilla 3D 4x12', this)">
                            Sentadilla 3D 4x12
                            <img src="https://image.made-in-china.com/2f0j00CjTEtZGMRQpz/Top-Quality-Hoist-Fitness-Equipment-Squat-Smith-SR1-32-.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Sentadilla Smith 4x12', this)">
                            Sentadilla Smith 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/smith-machine-squat/smith-machine-squat-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Hip Thrust con Barra 4x12', this)">
                            Hip Thrust con Barra 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/hip-thrust/hip-thrust-800.jpg" alt="Peso muerto">
                        </button>

                    </div>

                    <h3>Cardio</h3>

                    <div class="exercises">

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Eliptica', this)">
                            Eliptica
                            <img src="https://caminadorasygimnasios.com/cdn/shop/products/43619794823_1_1200x1200.jpg?v=1660063333" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Caminadora', this)">
                            Caminadora
                            <img src="https://www.alphafitness.mx/wp-content/uploads/2021/04/Star-trac-etrx-led.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Bicicleta', this)">
                            Bicicleta
                            <img src="https://www.mercadazo.com.mx/115595-large_default/bicicleta-fija-ergo-bike-bimex-con-medidor-de-funciones.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Crunches en Maquina', this)">
                            Crunches en Maquina
                            <img src="https://static.strengthlevel.com/images/exercises/machine-seated-crunch/machine-seated-crunch-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Espalda Baja en Máquina', this)">
                            Espalda Baja en Máquina
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2016/10/S2ABB_Ex_F_2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Abdominales en Banco Abdominal 4x12', this)">
                            Abdominales en Banco Abdominal 4x12
                            <img src="https://contents.mediadecathlon.com/m10766761/k$6bdf2c6869bf06a99ca9a62e5aabd7b1/sq/250x250/Banco-De-Musculacion-Para-Abdominales-Vital-Gym-ES-507.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Abdominales en Banco de Crunches 4x12', this)">
                            Abdominales en Banco de Crunches 4x12
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2020/07/BFHYP10r_Hero-Folded_DSF2125-2000.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Martes', 'Abdominales en Banco de Flexor 4x12', this)">
                            Abdominales en Banco de Flexor 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYkIjc3YUc1sRkYLDZJtQaEJ0DZUkE1sgP0Q&s" alt="image">
                        </button>

                    </div>

                    <div class="selected-exercises" id="selected-exercises-Martes" style="display: none;">
                        <ul></ul>
                    </div>
                </div>

                <!-- EJERCICIOS MIERCOLES -->
                <div class="day-container">
                    <div class="day-title">Miércoles</div>

                    <h6>Tren superior</h6>

                    <div class="control-group full-width">
                        <label class="control-label">Título</label>
                        <div class="controls">
                            <input type="text" name="title_routineday_Wednesday" class="span3" value="<?php echo isset($routine) ? htmlspecialchars($routine['title_routineday_Wednesday']) : ''; ?>" />
                        </div>
                    </div>

                    <div class="exercises">

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Press de Pecho en Máquina Precor 4x12', this)">
                            Press de Pecho en Máquina Precor 4x12
                            <img src="https://www.precorhomefitness.com/cdn/shop/files/precor-vitality-series-chest-press-c001-475246.jpg?v=1712890834" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Press de Pecho Declinado 4x12', this)">
                            Press de Pecho Declinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/decline-bench-press/decline-bench-press-800.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Press de Pecho Plano 4x12', this)">
                            Press de Pecho Plano 4x12
                            <img src="https://i.blogs.es/0fdc2d/bench_press/450_1000.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Press de Pecho Inclinado 4x12', this)">
                            Press de Pecho Inclinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/incline-bench-press/incline-bench-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Press de Pecho Inclinado en Máquina 4x12', this)">
                            Press de Pecho Inclinado en Máquina 4x12
                            <img src="https://www.equipamientosfox.com/wp-content/uploads/2022/03/linea-full_lingotes_press-de-pecho-inclinado.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Press inclinado en Máquina Hammer 4x12', this)">
                            Press inclinado en Máquina Hammer 4x12
                            <img src="https://www.ukgymequipment.com/images/plate-loaded-iso-lateral-decline-chest-press-p1828-18873_image.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Pec Fly en Máquina 4x12', this)">
                            Pec Fly en Máquina 4x12
                            <img src="https://i.imgur.com/3QJ0nVg.jpeg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Pec Deck', this)">
                            Pec Deck 4x12
                            <img src="https://www.shutterstock.com/image-vector/man-doing-machine-bent-arm-600nw-2379165459.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Pet Fly en Máquina 4x12', this)">
                            Pet Fly en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7N7aQwXqt4Efy44U4gm8i9QRIpyzCXqplFgo67YVIHPIleAU2JUSsp9kBAsc_YIvGG_I&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Cross-Over Pecho 4x12', this)">
                            Cross-Over Pecho 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/cable-fly/cable-fly-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Remo en Máquina 4x12', this)">
                            Remo en Máquina 4x12
                            <img src="https://i.imgur.com/DZ0bjZi.jpeg" alt="Incline press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Remo con agarre en V 4x12', this)">
                            Remo con agarre en V 4x12
                            <img src="https://static.gym-training.com/upload/image/original/5e/05/5e053461b689209c7baaa60d4e97cc74.jpeg?ae17ef5dda5afeaaabf0214fa57e32c0" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Remo con Agarre Abierto 4x12', this)">
                            Remo con Agarre Abierto 4x12
                            <img src="https://s3assets.skimble.com/assets/1874224/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Remo T en Máquina 4x12', this)">
                            Remo T en Máquina 4x12
                            <img src="https://cdn.globalso.com/dhzfitness/Incline-Level-Row-U2061-2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Jalon en polea alta Espalda 4x12', this)">
                            Jalon en polea alta Espalda 4x12
                            <img src="https://s3assets.skimble.com/assets/2179480/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Remo Articulado en Máquina 4x12', this)">
                            Remo Articulado en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKMRDeNFa5SdOAzmgnxHSnkFmMD6W9MPq1G2pRCiMyvjvh007ap_lSdvsEbG9fp9vhi4k&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Remo alto en Máquina Hammer 4x12', this)">
                            Remo alto en Máquina Hammer 4x12
                            <img src="https://gfitness.fi/storage/attachments/nn6/bfp/dw1/nn6bfpdw19czxlavb29f26x2-800x_-resize.jpg?hammer-strength-iso-lateral-low-row" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Pull Down en Máquina 4x12', this)">
                            Pull Down en Máquina 4x12
                            <img src="https://musclemagfitness.com/wp-content/uploads/reverse-grip-lat-pulldown-exercise-300x300.jpg.webp" alt="Standing overhead press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Delt Fly en Máquina 4x12', this)">
                            Delt Fly en Máquina 4x12
                            <img src="https://s3assets.skimble.com/assets/1852429/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Curl de Biceps en Máquina Hammer 4x12', this)">
                            Curl de Biceps en Máquina Hammer 4x12
                            <img src="https://www.pinnaclefitness.org.uk/wp-content/uploads/2023/11/141-1.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Curl de Biceps en Maquina Cybex 4x1  ', this)">
                            Curl de Biceps en Maquina Cybex 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRk0u3UNyubTt_oVcVnSNlxfCe03UOjMcZ9YQ&s" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Fondos en Máquina 4x12', this)">
                            Fondos en Máquina 4x12
                            <img src="https://i5.walmartimages.com/asr/ac9dabc5-8127-4c22-8117-9c48e3cce196_1.d4a65f8056364a7a425314f016eab52f.jpeg?odnHeight=768&odnWidth=768&odnBg=FFFFFF" alt="Pull-ups">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Extencion de Triceps en Maquina', this)">
                            Extencion de Triceps en Máquina 4x12
                            <img src="https://s3.amazonaws.com/prod.skimble/assets/1871648/image_iphone.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Rack de Hombro Hammer', this)">
                            Press Militar con Barra 4 x 12
                            <img src="https://www.deportrainer.com/img/cms/Post%20de%20blog/ejercicios_hombro/ejercicio-press-militar-con-barra-sentado.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Press de Hombro en Maquina 4x12', this)">
                            Press de Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-shoulder-press/machine-shoulder-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Deltoides para Hombro en Máquina 4x12', this)">
                            Deltoides para Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-lateral-raise/machine-lateral-raise-800.jpg" alt="Chest supported row">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Hombro Lateral en Máquina 4x12', this)">
                            Hombro Lateral en Máquina 4x12
                            <img src="https://www.fullcirclepadding.com/assets/1/26/DimThumbnail/4065-Shoulder-Internal-External-Rotation-CYW102.jpg?34880" alt="Incline overhead extension">
                        </button>

                    </div>

                    <!--TREN INFERIOR -->
                    <h6>Tren inferior</h6>

                    <div class="exercises">

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Banco para Hipertensiones Espalda Baja', this)">
                            Banco para Hipertensiones Espalda Baja 4x12
                            <img src="https://www.ultrafitness.mx/412-home_default/banca-para-hipertensiones-espalda-baja.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Peso Muerto en Máquina', this)">
                            Peso Muerto en Maquina 4x12
                            <img src="https://lh5.googleusercontent.com/proxy/JBBLBQ6XanxRnc5NlX0tfxjagyh2_sY19NLPv7vJkw3lW99zMran8XJVKnhXUxb0vOHCVNA8VmBliQkkzPONq58" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Sentadilla Barra Libre 4x12', this)">
                            Sentadilla Barra Libre 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/squat/squat-800.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Hack Squat 4x12', this)">
                            Hack Squat 4x12
                            <img src="https://samsfitness.com.au/wp-content/uploads/2022/11/atx-bpr-790-hack-squatting.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Femoral Sentado en Máquina 4x12', this)">
                            Femoral Sentado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/seated-leg-curl/seated-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Curl Femoral de Pie 4x12', this)">
                            Curl Femoral de Pie 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/standing-leg-curl/standing-leg-curl-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Hack Horizontal en Máquina 4x12', this)">
                            Hack Horizontal en Máquina 4x12
                            <img src="https://www.lyfta.app/_next/image?url=%2Fthumbnails%2F07441201.jpg&w=3840&q=20" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Prensa Sentado 4x12', this)">
                            Prensa Sentado 4x12
                            <img src="https://cdn4.volusion.store/aqyor-dadrn/v/vspfiles/photos/LFPro1slp-2.jpg?v-cache=1719387531" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Extension de Cuadricep en Máquina 4x12', this)">
                            Extension de Cuadricep en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/leg-extension/leg-extension-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Femoral Acostado en Máquina 4x12', this)">
                            Femoral Acostado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/lying-leg-curl/lying-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Pantorilla Inclinado en Máquina 4x12', this)">
                            Pantorrilla Inclinado en Máquina
                            <img src="https://www.globalfitness.com/cdn/shop/products/precor-icarian-angled-seated-calf_fd638229-3d71-491a-82a3-152bf8748515.jpg?v=1548436124" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Multi Hip para Glúteo 4x12', this)">
                            Multi Hip para Glúteo 4x12
                            <img src="https://www.profitnessmx.com/image/cache/catalog/Inflight-Fitness/inflight-fitness-multi-hip-glute-2-500x500.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Patada Femoral-Glúteo 4x12', this)">
                            Patada Femoral-Glúteo 4x12
                            <img src="https://tkstar.com/cdn/shop/products/Precor-Glute-618-2.png?v=1695909425" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Press en Máquina Horizontal', this)">
                            Press en Máquina Horizontal 4x12
                            <img src="https://images.offerup.com/37qPierSIR9ELhS0tU-xsUuSeWA=/1440x1920/07ad/07adc89c342345d7a355bd9e111dda1e.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Hip Abductor 4x12', this)">
                            Hip Abductor 4x12
                            <img src="https://i.pinimg.com/originals/59/83/a8/5983a8508eaf3445277b95bf13efc6a6.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Adductor 4x12', this)">
                            Adductor 4x12
                            <img src="https://static.strengthlevel.com/images/illustrations/hip-adduction-1000x1000.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Leg Press en Hammer 4x12', this)">
                            Leg Press en Hammer 4x12
                            <img src="https://www.fitnessforlifemx.com/cdn/shop/products/PlateLoadedLegPress_1200x.png?v=1680795665" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Prensa a 45° 4x12', this)">
                            Prensa a 45° 4x12
                            <img src="https://guiafitness.com/wp-content/uploads/20080731psaeef_1_ies_lco.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Sentadilla Perfecta 4x12', this)">
                            Sentadilla Perfecta 4x12
                            <img src="https://www.ultrafitness.mx/29-large_default/aparato-para-sentadilla-y-pantorrilla-parado-body-solid-gscl360.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Costurera Patorrilla 4x12', this)">
                            Costurera Patorrilla 4x12
                            <img src="https://importfitness.com/wp-content/uploads/2023/10/WhatsApp-Image-2023-05-30-at-12.42.55-PM-51.jpeg.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Prensa Pendulo Articulada 4x12', this)">
                            Prensa Pendulo Articulada 4x12
                            <img src="https://http2.mlstatic.com/D_NQ_NP_693194-MLM51995845620_102022-O.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Sentadilla 3D 4x12', this)">
                            Sentadilla 3D 4x12
                            <img src="https://image.made-in-china.com/2f0j00CjTEtZGMRQpz/Top-Quality-Hoist-Fitness-Equipment-Squat-Smith-SR1-32-.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Sentadilla Smith 4x12', this)">
                            Sentadilla Smith 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/smith-machine-squat/smith-machine-squat-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Hip Thrust con Barra 4x12', this)">
                            Hip Thrust con Barra 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/hip-thrust/hip-thrust-800.jpg" alt="Peso muerto">
                        </button>

                    </div>

                    <h3>Cardio</h3>

                    <div class="exercises">

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Eliptica', this)">
                            Eliptica
                            <img src="https://caminadorasygimnasios.com/cdn/shop/products/43619794823_1_1200x1200.jpg?v=1660063333" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Caminadora', this)">
                            Caminadora
                            <img src="https://www.alphafitness.mx/wp-content/uploads/2021/04/Star-trac-etrx-led.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Bicicleta', this)">
                            Bicicleta
                            <img src="https://www.mercadazo.com.mx/115595-large_default/bicicleta-fija-ergo-bike-bimex-con-medidor-de-funciones.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Crunches en Maquina', this)">
                            Crunches en Maquina
                            <img src="https://static.strengthlevel.com/images/exercises/machine-seated-crunch/machine-seated-crunch-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Espalda Baja en Máquina', this)">
                            Espalda Baja en Máquina
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2016/10/S2ABB_Ex_F_2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Abdominales en Banco Abdominal 4x12', this)">
                            Abdominales en Banco Abdominal 4x12
                            <img src="https://contents.mediadecathlon.com/m10766761/k$6bdf2c6869bf06a99ca9a62e5aabd7b1/sq/250x250/Banco-De-Musculacion-Para-Abdominales-Vital-Gym-ES-507.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Abdominales en Banco de Crunches 4x12', this)">
                            Abdominales en Banco de Crunches 4x12
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2020/07/BFHYP10r_Hero-Folded_DSF2125-2000.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Miercoles', 'Abdominales en Banco de Flexor 4x12', this)">
                            Abdominales en Banco de Flexor 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYkIjc3YUc1sRkYLDZJtQaEJ0DZUkE1sgP0Q&s" alt="image">
                        </button>

                    </div>

                    <div class="selected-exercises" id="selected-exercises-Miercoles" style="display: none;">
                        <ul></ul>
                    </div>
                </div>


                <!-- EJERCICIOS JUEVES -->
                <div class="day-container">
                    <div class="day-title">Jueves</div>

                    <h6>Tren superior</h6>

                    <div class="control-group full-width">
                        <label class="control-label">Título</label>
                        <div class="controls">
                            <input type="text" name="title_routineday_Thursday" class="span3" value="<?php echo isset($routine) ? htmlspecialchars($routine['title_routineday_Thursday']) : ''; ?>" />
                        </div>
                    </div>

                    <div class="exercises">


                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Press de Pecho en Máquina Precor 4x12', this)">
                            Press de Pecho en Máquina Precor 4x12
                            <img src="https://www.precorhomefitness.com/cdn/shop/files/precor-vitality-series-chest-press-c001-475246.jpg?v=1712890834" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Press de Pecho Declinado 4x12', this)">
                            Press de Pecho Declinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/decline-bench-press/decline-bench-press-800.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Press de Pecho Plano 4x12', this)">
                            Press de Pecho Plano 4x12
                            <img src="https://i.blogs.es/0fdc2d/bench_press/450_1000.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Press de Pecho Inclinado 4x12', this)">
                            Press de Pecho Inclinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/incline-bench-press/incline-bench-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Press de Pecho Inclinado en Máquina 4x12', this)">
                            Press de Pecho Inclinado en Máquina 4x12
                            <img src="https://www.equipamientosfox.com/wp-content/uploads/2022/03/linea-full_lingotes_press-de-pecho-inclinado.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Press inclinado en Máquina Hammer 4x12', this)">
                            Press inclinado en Máquina Hammer 4x12
                            <img src="https://www.ukgymequipment.com/images/plate-loaded-iso-lateral-decline-chest-press-p1828-18873_image.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Pec Fly en Máquina 4x12', this)">
                            Pec Fly en Máquina 4x12
                            <img src="https://i.imgur.com/3QJ0nVg.jpeg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Pec Deck', this)">
                            Pec Deck 4x12
                            <img src="https://www.shutterstock.com/image-vector/man-doing-machine-bent-arm-600nw-2379165459.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Pet Fly en Máquina 4x12', this)">
                            Pet Fly en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7N7aQwXqt4Efy44U4gm8i9QRIpyzCXqplFgo67YVIHPIleAU2JUSsp9kBAsc_YIvGG_I&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Cross-Over Pecho 4x12', this)">
                            Cross-Over Pecho 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/cable-fly/cable-fly-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Remo en Máquina 4x12', this)">
                            Remo en Máquina 4x12
                            <img src="https://i.imgur.com/DZ0bjZi.jpeg" alt="Incline press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Remo con agarre en V 4x12', this)">
                            Remo con agarre en V 4x12
                            <img src="https://static.gym-training.com/upload/image/original/5e/05/5e053461b689209c7baaa60d4e97cc74.jpeg?ae17ef5dda5afeaaabf0214fa57e32c0" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Remo con Agarre Abierto 4x12', this)">
                            Remo con Agarre Abierto 4x12
                            <img src="https://s3assets.skimble.com/assets/1874224/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Remo T en Máquina 4x12', this)">
                            Remo T en Máquina 4x12
                            <img src="https://cdn.globalso.com/dhzfitness/Incline-Level-Row-U2061-2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Jalon en polea alta Espalda 4x12', this)">
                            Jalon en polea alta Espalda 4x12
                            <img src="https://s3assets.skimble.com/assets/2179480/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Remo Articulado en Máquina 4x12', this)">
                            Remo Articulado en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKMRDeNFa5SdOAzmgnxHSnkFmMD6W9MPq1G2pRCiMyvjvh007ap_lSdvsEbG9fp9vhi4k&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Remo alto en Máquina Hammer 4x12', this)">
                            Remo alto en Máquina Hammer 4x12
                            <img src="https://gfitness.fi/storage/attachments/nn6/bfp/dw1/nn6bfpdw19czxlavb29f26x2-800x_-resize.jpg?hammer-strength-iso-lateral-low-row" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Pull Down en Máquina 4x12', this)">
                            Pull Down en Máquina 4x12
                            <img src="https://musclemagfitness.com/wp-content/uploads/reverse-grip-lat-pulldown-exercise-300x300.jpg.webp" alt="Standing overhead press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Delt Fly en Máquina 4x12', this)">
                            Delt Fly en Máquina 4x12
                            <img src="https://s3assets.skimble.com/assets/1852429/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Curl de Biceps en Máquina Hammer 4x12', this)">
                            Curl de Biceps en Máquina Hammer 4x12
                            <img src="https://www.pinnaclefitness.org.uk/wp-content/uploads/2023/11/141-1.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Curl de Biceps en Maquina Cybex 4x1  ', this)">
                            Curl de Biceps en Maquina Cybex 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRk0u3UNyubTt_oVcVnSNlxfCe03UOjMcZ9YQ&s" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Fondos en Máquina 4x12', this)">
                            Fondos en Máquina 4x12
                            <img src="https://i5.walmartimages.com/asr/ac9dabc5-8127-4c22-8117-9c48e3cce196_1.d4a65f8056364a7a425314f016eab52f.jpeg?odnHeight=768&odnWidth=768&odnBg=FFFFFF" alt="Pull-ups">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Extencion de Triceps en Maquina', this)">
                            Extencion de Triceps en Máquina 4x12
                            <img src="https://s3.amazonaws.com/prod.skimble/assets/1871648/image_iphone.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Rack de Hombro Hammer', this)">
                            Press Militar con Barra 4 x 12
                            <img src="https://www.deportrainer.com/img/cms/Post%20de%20blog/ejercicios_hombro/ejercicio-press-militar-con-barra-sentado.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Press de Hombro en Maquina 4x12', this)">
                            Press de Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-shoulder-press/machine-shoulder-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Deltoides para Hombro en Máquina 4x12', this)">
                            Deltoides para Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-lateral-raise/machine-lateral-raise-800.jpg" alt="Chest supported row">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Hombro Lateral en Máquina 4x12', this)">
                            Hombro Lateral en Máquina 4x12
                            <img src="https://www.fullcirclepadding.com/assets/1/26/DimThumbnail/4065-Shoulder-Internal-External-Rotation-CYW102.jpg?34880" alt="Incline overhead extension">
                        </button>

                    </div>

                    <!--TREN INFERIOR -->
                    <h6>Tren inferior</h6>

                    <div class="exercises">

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Banco para Hipertensiones Espalda Baja', this)">
                            Banco para Hipertensiones Espalda Baja 4x12
                            <img src="https://www.ultrafitness.mx/412-home_default/banca-para-hipertensiones-espalda-baja.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Peso Muerto en Máquina', this)">
                            Peso Muerto en Maquina 4x12
                            <img src="https://lh5.googleusercontent.com/proxy/JBBLBQ6XanxRnc5NlX0tfxjagyh2_sY19NLPv7vJkw3lW99zMran8XJVKnhXUxb0vOHCVNA8VmBliQkkzPONq58" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Sentadilla Barra Libre 4x12', this)">
                            Sentadilla Barra Libre 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/squat/squat-800.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Hack Squat 4x12', this)">
                            Hack Squat 4x12
                            <img src="https://samsfitness.com.au/wp-content/uploads/2022/11/atx-bpr-790-hack-squatting.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Femoral Sentado en Máquina 4x12', this)">
                            Femoral Sentado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/seated-leg-curl/seated-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Curl Femoral de Pie 4x12', this)">
                            Curl Femoral de Pie 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/standing-leg-curl/standing-leg-curl-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Hack Horizontal en Máquina 4x12', this)">
                            Hack Horizontal en Máquina 4x12
                            <img src="https://www.lyfta.app/_next/image?url=%2Fthumbnails%2F07441201.jpg&w=3840&q=20" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Prensa Sentado 4x12', this)">
                            Prensa Sentado 4x12
                            <img src="https://cdn4.volusion.store/aqyor-dadrn/v/vspfiles/photos/LFPro1slp-2.jpg?v-cache=1719387531" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Extension de Cuadricep en Máquina 4x12', this)">
                            Extension de Cuadricep en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/leg-extension/leg-extension-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Femoral Acostado en Máquina 4x12', this)">
                            Femoral Acostado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/lying-leg-curl/lying-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Pantorilla Inclinado en Máquina 4x12', this)">
                            Pantorrilla Inclinado en Máquina
                            <img src="https://www.globalfitness.com/cdn/shop/products/precor-icarian-angled-seated-calf_fd638229-3d71-491a-82a3-152bf8748515.jpg?v=1548436124" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Multi Hip para Glúteo 4x12', this)">
                            Multi Hip para Glúteo 4x12
                            <img src="https://www.profitnessmx.com/image/cache/catalog/Inflight-Fitness/inflight-fitness-multi-hip-glute-2-500x500.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Patada Femoral-Glúteo 4x12', this)">
                            Patada Femoral-Glúteo 4x12
                            <img src="https://tkstar.com/cdn/shop/products/Precor-Glute-618-2.png?v=1695909425" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Press en Máquina Horizontal', this)">
                            Press en Máquina Horizontal 4x12
                            <img src="https://images.offerup.com/37qPierSIR9ELhS0tU-xsUuSeWA=/1440x1920/07ad/07adc89c342345d7a355bd9e111dda1e.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Hip Abductor 4x12', this)">
                            Hip Abductor 4x12
                            <img src="https://i.pinimg.com/originals/59/83/a8/5983a8508eaf3445277b95bf13efc6a6.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Adductor 4x12', this)">
                            Adductor 4x12
                            <img src="https://static.strengthlevel.com/images/illustrations/hip-adduction-1000x1000.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Leg Press en Hammer 4x12', this)">
                            Leg Press en Hammer 4x12
                            <img src="https://www.fitnessforlifemx.com/cdn/shop/products/PlateLoadedLegPress_1200x.png?v=1680795665" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Prensa a 45° 4x12', this)">
                            Prensa a 45° 4x12
                            <img src="https://guiafitness.com/wp-content/uploads/20080731psaeef_1_ies_lco.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Sentadilla Perfecta 4x12', this)">
                            Sentadilla Perfecta 4x12
                            <img src="https://www.ultrafitness.mx/29-large_default/aparato-para-sentadilla-y-pantorrilla-parado-body-solid-gscl360.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Costurera Patorrilla 4x12', this)">
                            Costurera Patorrilla 4x12
                            <img src="https://importfitness.com/wp-content/uploads/2023/10/WhatsApp-Image-2023-05-30-at-12.42.55-PM-51.jpeg.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Prensa Pendulo Articulada 4x12', this)">
                            Prensa Pendulo Articulada 4x12
                            <img src="https://http2.mlstatic.com/D_NQ_NP_693194-MLM51995845620_102022-O.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Sentadilla 3D 4x12', this)">
                            Sentadilla 3D 4x12
                            <img src="https://image.made-in-china.com/2f0j00CjTEtZGMRQpz/Top-Quality-Hoist-Fitness-Equipment-Squat-Smith-SR1-32-.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Sentadilla Smith 4x12', this)">
                            Sentadilla Smith 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/smith-machine-squat/smith-machine-squat-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Hip Thrust con Barra 4x12', this)">
                            Hip Thrust con Barra 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/hip-thrust/hip-thrust-800.jpg" alt="Peso muerto">
                        </button>

                    </div>

                    <h3>Cardio</h3>

                    <div class="exercises">

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Eliptica', this)">
                            Eliptica
                            <img src="https://caminadorasygimnasios.com/cdn/shop/products/43619794823_1_1200x1200.jpg?v=1660063333" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Caminadora', this)">
                            Caminadora
                            <img src="https://www.alphafitness.mx/wp-content/uploads/2021/04/Star-trac-etrx-led.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Bicicleta', this)">
                            Bicicleta
                            <img src="https://www.mercadazo.com.mx/115595-large_default/bicicleta-fija-ergo-bike-bimex-con-medidor-de-funciones.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Crunches en Maquina', this)">
                            Crunches en Maquina
                            <img src="https://static.strengthlevel.com/images/exercises/machine-seated-crunch/machine-seated-crunch-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Espalda Baja en Máquina', this)">
                            Espalda Baja en Máquina
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2016/10/S2ABB_Ex_F_2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Abdominales en Banco Abdominal 4x12', this)">
                            Abdominales en Banco Abdominal 4x12
                            <img src="https://contents.mediadecathlon.com/m10766761/k$6bdf2c6869bf06a99ca9a62e5aabd7b1/sq/250x250/Banco-De-Musculacion-Para-Abdominales-Vital-Gym-ES-507.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Abdominales en Banco de Crunches 4x12', this)">
                            Abdominales en Banco de Crunches 4x12
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2020/07/BFHYP10r_Hero-Folded_DSF2125-2000.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Jueves', 'Abdominales en Banco de Flexor 4x12', this)">
                            Abdominales en Banco de Flexor 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYkIjc3YUc1sRkYLDZJtQaEJ0DZUkE1sgP0Q&s" alt="image">
                        </button>

                    </div>

                    <div class="selected-exercises" id="selected-exercises-Jueves" style="display: none;">
                        <ul></ul>
                    </div>
                </div>


                <!-- EJERCICIOS VIERNES -->
                <div class="day-container">
                    <div class="day-title">Viernes</div>


                    <h6>Tren superior</h6>

                    <div class="control-group full-width">
                        <label class="control-label">Título</label>
                        <div class="controls">
                            <input type="text" name="title_routineday_Friday" class="span3" value="<?php echo isset($routine) ? htmlspecialchars($routine['title_routineday_Friday']) : ''; ?>" />
                        </div>
                    </div>

                    <div class="exercises">


                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Press de Pecho en Máquina Precor 4x12', this)">
                            Press de Pecho en Máquina Precor 4x12
                            <img src="https://www.precorhomefitness.com/cdn/shop/files/precor-vitality-series-chest-press-c001-475246.jpg?v=1712890834" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Press de Pecho Declinado 4x12', this)">
                            Press de Pecho Declinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/decline-bench-press/decline-bench-press-800.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Press de Pecho Plano 4x12', this)">
                            Press de Pecho Plano 4x12
                            <img src="https://i.blogs.es/0fdc2d/bench_press/450_1000.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Press de Pecho Inclinado 4x12', this)">
                            Press de Pecho Inclinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/incline-bench-press/incline-bench-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Press de Pecho Inclinado en Máquina 4x12', this)">
                            Press de Pecho Inclinado en Máquina 4x12
                            <img src="https://www.equipamientosfox.com/wp-content/uploads/2022/03/linea-full_lingotes_press-de-pecho-inclinado.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Press inclinado en Máquina Hammer 4x12', this)">
                            Press inclinado en Máquina Hammer 4x12
                            <img src="https://www.ukgymequipment.com/images/plate-loaded-iso-lateral-decline-chest-press-p1828-18873_image.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Pec Fly en Máquina 4x12', this)">
                            Pec Fly en Máquina 4x12
                            <img src="https://i.imgur.com/3QJ0nVg.jpeg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Pec Deck', this)">
                            Pec Deck 4x12
                            <img src="https://www.shutterstock.com/image-vector/man-doing-machine-bent-arm-600nw-2379165459.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Pet Fly en Máquina 4x12', this)">
                            Pet Fly en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7N7aQwXqt4Efy44U4gm8i9QRIpyzCXqplFgo67YVIHPIleAU2JUSsp9kBAsc_YIvGG_I&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Cross-Over Pecho 4x12', this)">
                            Cross-Over Pecho 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/cable-fly/cable-fly-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Remo en Máquina 4x12', this)">
                            Remo en Máquina 4x12
                            <img src="https://i.imgur.com/DZ0bjZi.jpeg" alt="Incline press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Remo con agarre en V 4x12', this)">
                            Remo con agarre en V 4x12
                            <img src="https://static.gym-training.com/upload/image/original/5e/05/5e053461b689209c7baaa60d4e97cc74.jpeg?ae17ef5dda5afeaaabf0214fa57e32c0" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Remo con Agarre Abierto 4x12', this)">
                            Remo con Agarre Abierto 4x12
                            <img src="https://s3assets.skimble.com/assets/1874224/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Remo T en Máquina 4x12', this)">
                            Remo T en Máquina 4x12
                            <img src="https://cdn.globalso.com/dhzfitness/Incline-Level-Row-U2061-2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Jalon en polea alta Espalda 4x12', this)">
                            Jalon en polea alta Espalda 4x12
                            <img src="https://s3assets.skimble.com/assets/2179480/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Remo Articulado en Máquina 4x12', this)">
                            Remo Articulado en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKMRDeNFa5SdOAzmgnxHSnkFmMD6W9MPq1G2pRCiMyvjvh007ap_lSdvsEbG9fp9vhi4k&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Remo alto en Máquina Hammer 4x12', this)">
                            Remo alto en Máquina Hammer 4x12
                            <img src="https://gfitness.fi/storage/attachments/nn6/bfp/dw1/nn6bfpdw19czxlavb29f26x2-800x_-resize.jpg?hammer-strength-iso-lateral-low-row" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Pull Down en Máquina 4x12', this)">
                            Pull Down en Máquina 4x12
                            <img src="https://musclemagfitness.com/wp-content/uploads/reverse-grip-lat-pulldown-exercise-300x300.jpg.webp" alt="Standing overhead press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Delt Fly en Máquina 4x12', this)">
                            Delt Fly en Máquina 4x12
                            <img src="https://s3assets.skimble.com/assets/1852429/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Curl de Biceps en Máquina Hammer 4x12', this)">
                            Curl de Biceps en Máquina Hammer 4x12
                            <img src="https://www.pinnaclefitness.org.uk/wp-content/uploads/2023/11/141-1.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Curl de Biceps en Maquina Cybex 4x1  ', this)">
                            Curl de Biceps en Maquina Cybex 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRk0u3UNyubTt_oVcVnSNlxfCe03UOjMcZ9YQ&s" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Fondos en Máquina 4x12', this)">
                            Fondos en Máquina 4x12
                            <img src="https://i5.walmartimages.com/asr/ac9dabc5-8127-4c22-8117-9c48e3cce196_1.d4a65f8056364a7a425314f016eab52f.jpeg?odnHeight=768&odnWidth=768&odnBg=FFFFFF" alt="Pull-ups">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Extencion de Triceps en Maquina', this)">
                            Extencion de Triceps en Máquina 4x12
                            <img src="https://s3.amazonaws.com/prod.skimble/assets/1871648/image_iphone.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Rack de Hombro Hammer', this)">
                            Press Militar con Barra 4 x 12
                            <img src="https://www.deportrainer.com/img/cms/Post%20de%20blog/ejercicios_hombro/ejercicio-press-militar-con-barra-sentado.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Press de Hombro en Maquina 4x12', this)">
                            Press de Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-shoulder-press/machine-shoulder-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Deltoides para Hombro en Máquina 4x12', this)">
                            Deltoides para Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-lateral-raise/machine-lateral-raise-800.jpg" alt="Chest supported row">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Hombro Lateral en Máquina 4x12', this)">
                            Hombro Lateral en Máquina 4x12
                            <img src="https://www.fullcirclepadding.com/assets/1/26/DimThumbnail/4065-Shoulder-Internal-External-Rotation-CYW102.jpg?34880" alt="Incline overhead extension">
                        </button>

                    </div>

                    <!--TREN INFERIOR VIERNES-->
                    <h6>Tren inferior</h6>

                    <div class="exercises">

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Banco para Hipertensiones Espalda Baja', this)">
                            Banco para Hipertensiones Espalda Baja 4x12
                            <img src="https://www.ultrafitness.mx/412-home_default/banca-para-hipertensiones-espalda-baja.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Peso Muerto en Máquina', this)">
                            Peso Muerto en Maquina 4x12
                            <img src="https://lh5.googleusercontent.com/proxy/JBBLBQ6XanxRnc5NlX0tfxjagyh2_sY19NLPv7vJkw3lW99zMran8XJVKnhXUxb0vOHCVNA8VmBliQkkzPONq58" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Sentadilla Barra Libre 4x12', this)">
                            Sentadilla Barra Libre 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/squat/squat-800.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Hack Squat 4x12', this)">
                            Hack Squat 4x12
                            <img src="https://samsfitness.com.au/wp-content/uploads/2022/11/atx-bpr-790-hack-squatting.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Femoral Sentado en Máquina 4x12', this)">
                            Femoral Sentado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/seated-leg-curl/seated-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Curl Femoral de Pie 4x12', this)">
                            Curl Femoral de Pie 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/standing-leg-curl/standing-leg-curl-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Hack Horizontal en Máquina 4x12', this)">
                            Hack Horizontal en Máquina 4x12
                            <img src="https://www.lyfta.app/_next/image?url=%2Fthumbnails%2F07441201.jpg&w=3840&q=20" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Prensa Sentado 4x12', this)">
                            Prensa Sentado 4x12
                            <img src="https://cdn4.volusion.store/aqyor-dadrn/v/vspfiles/photos/LFPro1slp-2.jpg?v-cache=1719387531" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Extension de Cuadricep en Máquina 4x12', this)">
                            Extension de Cuadricep en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/leg-extension/leg-extension-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Femoral Acostado en Máquina 4x12', this)">
                            Femoral Acostado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/lying-leg-curl/lying-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Pantorilla Inclinado en Máquina 4x12', this)">
                            Pantorrilla Inclinado en Máquina
                            <img src="https://www.globalfitness.com/cdn/shop/products/precor-icarian-angled-seated-calf_fd638229-3d71-491a-82a3-152bf8748515.jpg?v=1548436124" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Multi Hip para Glúteo 4x12', this)">
                            Multi Hip para Glúteo 4x12
                            <img src="https://www.profitnessmx.com/image/cache/catalog/Inflight-Fitness/inflight-fitness-multi-hip-glute-2-500x500.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Patada Femoral-Glúteo 4x12', this)">
                            Patada Femoral-Glúteo 4x12
                            <img src="https://tkstar.com/cdn/shop/products/Precor-Glute-618-2.png?v=1695909425" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Press en Máquina Horizontal', this)">
                            Press en Máquina Horizontal 4x12
                            <img src="https://images.offerup.com/37qPierSIR9ELhS0tU-xsUuSeWA=/1440x1920/07ad/07adc89c342345d7a355bd9e111dda1e.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Hip Abductor 4x12', this)">
                            Hip Abductor 4x12
                            <img src="https://i.pinimg.com/originals/59/83/a8/5983a8508eaf3445277b95bf13efc6a6.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Adductor 4x12', this)">
                            Adductor 4x12
                            <img src="https://static.strengthlevel.com/images/illustrations/hip-adduction-1000x1000.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Leg Press en Hammer 4x12', this)">
                            Leg Press en Hammer 4x12
                            <img src="https://www.fitnessforlifemx.com/cdn/shop/products/PlateLoadedLegPress_1200x.png?v=1680795665" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Prensa a 45° 4x12', this)">
                            Prensa a 45° 4x12
                            <img src="https://guiafitness.com/wp-content/uploads/20080731psaeef_1_ies_lco.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Sentadilla Perfecta 4x12', this)">
                            Sentadilla Perfecta 4x12
                            <img src="https://www.ultrafitness.mx/29-large_default/aparato-para-sentadilla-y-pantorrilla-parado-body-solid-gscl360.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Costurera Patorrilla 4x12', this)">
                            Costurera Patorrilla 4x12
                            <img src="https://importfitness.com/wp-content/uploads/2023/10/WhatsApp-Image-2023-05-30-at-12.42.55-PM-51.jpeg.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Prensa Pendulo Articulada 4x12', this)">
                            Prensa Pendulo Articulada 4x12
                            <img src="https://http2.mlstatic.com/D_NQ_NP_693194-MLM51995845620_102022-O.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Sentadilla 3D 4x12', this)">
                            Sentadilla 3D 4x12
                            <img src="https://image.made-in-china.com/2f0j00CjTEtZGMRQpz/Top-Quality-Hoist-Fitness-Equipment-Squat-Smith-SR1-32-.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Sentadilla Smith 4x12', this)">
                            Sentadilla Smith 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/smith-machine-squat/smith-machine-squat-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Hip Thrust con Barra 4x12', this)">
                            Hip Thrust con Barra 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/hip-thrust/hip-thrust-800.jpg" alt="Peso muerto">
                        </button>

                    </div>

                    <h3>Cardio</h3>

                    <div class="exercises">

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Eliptica', this)">
                            Eliptica
                            <img src="https://caminadorasygimnasios.com/cdn/shop/products/43619794823_1_1200x1200.jpg?v=1660063333" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Caminadora', this)">
                            Caminadora
                            <img src="https://www.alphafitness.mx/wp-content/uploads/2021/04/Star-trac-etrx-led.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Bicicleta', this)">
                            Bicicleta
                            <img src="https://www.mercadazo.com.mx/115595-large_default/bicicleta-fija-ergo-bike-bimex-con-medidor-de-funciones.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Crunches en Maquina', this)">
                            Crunches en Maquina
                            <img src="https://static.strengthlevel.com/images/exercises/machine-seated-crunch/machine-seated-crunch-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Espalda Baja en Máquina', this)">
                            Espalda Baja en Máquina
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2016/10/S2ABB_Ex_F_2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Abdominales en Banco Abdominal 4x12', this)">
                            Abdominales en Banco Abdominal 4x12
                            <img src="https://contents.mediadecathlon.com/m10766761/k$6bdf2c6869bf06a99ca9a62e5aabd7b1/sq/250x250/Banco-De-Musculacion-Para-Abdominales-Vital-Gym-ES-507.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Abdominales en Banco de Crunches 4x12', this)">
                            Abdominales en Banco de Crunches 4x12
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2020/07/BFHYP10r_Hero-Folded_DSF2125-2000.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-button" onclick="toggleExercise('Viernes', 'Abdominales en Banco de Flexor 4x12', this)">
                            Abdominales en Banco de Flexor 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYkIjc3YUc1sRkYLDZJtQaEJ0DZUkE1sgP0Q&s" alt="image">
                        </button>

                    </div>


                    <div class="selected-exercises" id="selected-exercises-Viernes" style="display: none;">
                        <ul></ul>
                    </div>
                </div>


                <!-- EJERCICIOS SABADO -->
                <div class="day-container">
                    <div class="day-title">Sabado</div>


                    <h6>Tren superior</h6>

                    <div class="control-group full-width">
                        <label class="control-label">Título</label>
                        <div class="controls">
                            <input type="text" name="title_routineday_Saturday" class="span3" value="<?php echo isset($routine) ? htmlspecialchars($routine['title_routineday_Saturday']) : ''; ?>" />
                        </div>
                    </div>


                    <div class="exercises">


                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Press de Pecho en Máquina Precor 4x12', this)">
                            Press de Pecho en Máquina Precor 4x12
                            <img src="https://www.precorhomefitness.com/cdn/shop/files/precor-vitality-series-chest-press-c001-475246.jpg?v=1712890834" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Press de Pecho Declinado 4x12', this)">
                            Press de Pecho Declinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/decline-bench-press/decline-bench-press-800.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Press de Pecho Plano 4x12', this)">
                            Press de Pecho Plano 4x12
                            <img src="https://i.blogs.es/0fdc2d/bench_press/450_1000.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Press de Pecho Inclinado 4x12', this)">
                            Press de Pecho Inclinado 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/incline-bench-press/incline-bench-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Press de Pecho Inclinado en Máquina 4x12', this)">
                            Press de Pecho Inclinado en Máquina 4x12
                            <img src="https://www.equipamientosfox.com/wp-content/uploads/2022/03/linea-full_lingotes_press-de-pecho-inclinado.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Press inclinado en Máquina Hammer 4x12', this)">
                            Press inclinado en Máquina Hammer 4x12
                            <img src="https://www.ukgymequipment.com/images/plate-loaded-iso-lateral-decline-chest-press-p1828-18873_image.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Pec Fly en Máquina 4x12', this)">
                            Pec Fly en Máquina 4x12
                            <img src="https://i.imgur.com/3QJ0nVg.jpeg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Pec Deck', this)">
                            Pec Deck 4x12
                            <img src="https://www.shutterstock.com/image-vector/man-doing-machine-bent-arm-600nw-2379165459.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Pet Fly en Máquina 4x12', this)">
                            Pet Fly en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7N7aQwXqt4Efy44U4gm8i9QRIpyzCXqplFgo67YVIHPIleAU2JUSsp9kBAsc_YIvGG_I&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Cross-Over Pecho 4x12', this)">
                            Cross-Over Pecho 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/cable-fly/cable-fly-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Remo en Máquina 4x12', this)">
                            Remo en Máquina 4x12
                            <img src="https://i.imgur.com/DZ0bjZi.jpeg" alt="Incline press">
                            </bSabado <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Remo con agarre en V 4x12', this)">
                            Remo con agarre en V 4x12
                            <img src="https://static.gym-training.com/upload/image/original/5e/05/5e053461b689209c7baaa60d4e97cc74.jpeg?ae17ef5dda5afeaaabf0214fa57e32c0" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Remo con Agarre Abierto 4x12', this)">
                            Remo con Agarre Abierto 4x12
                            <img src="https://s3assets.skimble.com/assets/1874224/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Remo T en Máquina 4x12', this)">
                            Remo T en Máquina 4x12
                            <img src="https://cdn.globalso.com/dhzfitness/Incline-Level-Row-U2061-2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Jalon en polea alta Espalda 4x12', this)">
                            Jalon en polea alta Espalda 4x12
                            <img src="https://s3assets.skimble.com/assets/2179480/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Remo Articulado en Máquina 4x12', this)">
                            Remo Articulado en Máquina 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKMRDeNFa5SdOAzmgnxHSnkFmMD6W9MPq1G2pRCiMyvjvh007ap_lSdvsEbG9fp9vhi4k&usqp=CAU" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Remo alto en Máquina Hammer 4x12', this)">
                            Remo alto en Máquina Hammer 4x12
                            <img src="https://gfitness.fi/storage/attachments/nn6/bfp/dw1/nn6bfpdw19czxlavb29f26x2-800x_-resize.jpg?hammer-strength-iso-lateral-low-row" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Pull Down en Máquina 4x12', this)">
                            Pull Down en Máquina 4x12
                            <img src="https://musclemagfitness.com/wp-content/uploads/reverse-grip-lat-pulldown-exercise-300x300.jpg.webp" alt="Standing overhead press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Delt Fly en Máquina 4x12', this)">
                            Delt Fly en Máquina 4x12
                            <img src="https://s3assets.skimble.com/assets/1852429/image_iphone.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Curl de Biceps en Máquina Hammer 4x12', this)">
                            Curl de Biceps en Máquina Hammer 4x12
                            <img src="https://www.pinnaclefitness.org.uk/wp-content/uploads/2023/11/141-1.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Curl de Biceps en Maquina Cybex 4x1  ', this)">
                            Curl de Biceps en Maquina Cybex 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRk0u3UNyubTt_oVcVnSNlxfCe03UOjMcZ9YQ&s" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Fondos en Máquina 4x12', this)">
                            Fondos en Máquina 4x12
                            <img src="https://i5.walmartimages.com/asr/ac9dabc5-8127-4c22-8117-9c48e3cce196_1.d4a65f8056364a7a425314f016eab52f.jpeg?odnHeight=768&odnWidth=768&odnBg=FFFFFF" alt="Pull-ups">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Extencion de Triceps en Maquina', this)">
                            Extencion de Triceps en Máquina 4x12
                            <img src="https://s3.amazonaws.com/prod.skimble/assets/1871648/image_iphone.jpg" alt="Shoulder Press">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Rack de Hombro Hammer', this)">
                            Press Militar con Barra 4 x 12
                            <img src="https://www.deportrainer.com/img/cms/Post%20de%20blog/ejercicios_hombro/ejercicio-press-militar-con-barra-sentado.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Press de Hombro en Maquina 4x12', this)">
                            Press de Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-shoulder-press/machine-shoulder-press-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Deltoides para Hombro en Máquina 4x12', this)">
                            Deltoides para Hombro en Maquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/machine-lateral-raise/machine-lateral-raise-800.jpg" alt="Chest supported row">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Hombro Lateral en Máquina 4x12', this)">
                            Hombro Lateral en Máquina 4x12
                            <img src="https://www.fullcirclepadding.com/assets/1/26/DimThumbnail/4065-Shoulder-Internal-External-Rotation-CYW102.jpg?34880" alt="Incline overhead extension">
                        </button>

                    </div>

                    <!--TREN INFERIOR SABADO-->
                    <h6>Tren inferior</h6>

                    <div class="exercises">

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Banco para Hipertensiones Espalda Baja', this)">
                            Banco para Hipertensiones Espalda Baja 4x12
                            <img src="https://www.ultrafitness.mx/412-home_default/banca-para-hipertensiones-espalda-baja.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Peso Muerto en Máquina', this)">
                            Peso Muerto en Maquina 4x12
                            <img src="https://lh5.googleusercontent.com/proxy/JBBLBQ6XanxRnc5NlX0tfxjagyh2_sY19NLPv7vJkw3lW99zMran8XJVKnhXUxb0vOHCVNA8VmBliQkkzPONq58" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Sentadilla Barra Libre 4x12', this)">
                            Sentadilla Barra Libre 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/squat/squat-800.jpg" alt="Sentadillas">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Hack Squat 4x12', this)">
                            Hack Squat 4x12
                            <img src="https://samsfitness.com.au/wp-content/uploads/2022/11/atx-bpr-790-hack-squatting.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Femoral Sentado en Máquina 4x12', this)">
                            Femoral Sentado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/seated-leg-curl/seated-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Curl Femoral de Pie 4x12', this)">
                            Curl Femoral de Pie 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/standing-leg-curl/standing-leg-curl-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Hack Horizontal en Máquina 4x12', this)">
                            Hack Horizontal en Máquina 4x12
                            <img src="https://www.lyfta.app/_next/image?url=%2Fthumbnails%2F07441201.jpg&w=3840&q=20" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Prensa Sentado 4x12', this)">
                            Prensa Sentado 4x12
                            <img src="https://cdn4.volusion.store/aqyor-dadrn/v/vspfiles/photos/LFPro1slp-2.jpg?v-cache=1719387531" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Extension de Cuadricep en Máquina 4x12', this)">
                            Extension de Cuadricep en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/leg-extension/leg-extension-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Femoral Acostado en Máquina 4x12', this)">
                            Femoral Acostado en Máquina 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/lying-leg-curl/lying-leg-curl-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Pantorilla Inclinado en Máquina 4x12', this)">
                            Pantorrilla Inclinado en Máquina
                            <img src="https://www.globalfitness.com/cdn/shop/products/precor-icarian-angled-seated-calf_fd638229-3d71-491a-82a3-152bf8748515.jpg?v=1548436124" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Multi Hip para Glúteo 4x12', this)">
                            Multi Hip para Glúteo 4x12
                            <img src="https://www.profitnessmx.com/image/cache/catalog/Inflight-Fitness/inflight-fitness-multi-hip-glute-2-500x500.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Patada Femoral-Glúteo 4x12', this)">
                            Patada Femoral-Glúteo 4x12
                            <img src="https://tkstar.com/cdn/shop/products/Precor-Glute-618-2.png?v=1695909425" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Press en Máquina Horizontal', this)">
                            Press en Máquina Horizontal 4x12
                            <img src="https://images.offerup.com/37qPierSIR9ELhS0tU-xsUuSeWA=/1440x1920/07ad/07adc89c342345d7a355bd9e111dda1e.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Hip Abductor 4x12', this)">
                            Hip Abductor 4x12
                            <img src="https://i.pinimg.com/originals/59/83/a8/5983a8508eaf3445277b95bf13efc6a6.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Adductor 4x12', this)">
                            Adductor 4x12
                            <img src="https://static.strengthlevel.com/images/illustrations/hip-adduction-1000x1000.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Leg Press en Hammer 4x12', this)">
                            Leg Press en Hammer 4x12
                            <img src="https://www.fitnessforlifemx.com/cdn/shop/products/PlateLoadedLegPress_1200x.png?v=1680795665" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Prensa a 45° 4x12', this)">
                            Prensa a 45° 4x12
                            <img src="https://guiafitness.com/wp-content/uploads/20080731psaeef_1_ies_lco.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Sentadilla Perfecta 4x12', this)">
                            Sentadilla Perfecta 4x12
                            <img src="https://www.ultrafitness.mx/29-large_default/aparato-para-sentadilla-y-pantorrilla-parado-body-solid-gscl360.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Costurera Patorrilla 4x12', this)">
                            Costurera Patorrilla 4x12
                            <img src="https://importfitness.com/wp-content/uploads/2023/10/WhatsApp-Image-2023-05-30-at-12.42.55-PM-51.jpeg.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Prensa Pendulo Articulada 4x12', this)">
                            Prensa Pendulo Articulada 4x12
                            <img src="https://http2.mlstatic.com/D_NQ_NP_693194-MLM51995845620_102022-O.webp" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Sentadilla 3D 4x12', this)">
                            Sentadilla 3D 4x12
                            <img src="https://image.made-in-china.com/2f0j00CjTEtZGMRQpz/Top-Quality-Hoist-Fitness-Equipment-Squat-Smith-SR1-32-.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Sentadilla Smith 4x12', this)">
                            Sentadilla Smith 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/smith-machine-squat/smith-machine-squat-800.jpg" alt="Peso muerto">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Hip Thrust con Barra 4x12', this)">
                            Hip Thrust con Barra 4x12
                            <img src="https://static.strengthlevel.com/images/exercises/hip-thrust/hip-thrust-800.jpg" alt="Peso muerto">
                        </button>

                    </div>

                    <h3>Cardio</h3>

                    <div class="exercises">

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Eliptica', this)">
                            Eliptica
                            <img src="https://caminadorasygimnasios.com/cdn/shop/products/43619794823_1_1200x1200.jpg?v=1660063333" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Caminadora', this)">
                            Caminadora
                            <img src="https://www.alphafitness.mx/wp-content/uploads/2021/04/Star-trac-etrx-led.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Bicicleta', this)">
                            Bicicleta
                            <img src="https://www.mercadazo.com.mx/115595-large_default/bicicleta-fija-ergo-bike-bimex-con-medidor-de-funciones.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Crunches en Maquina', this)">
                            Crunches en Maquina
                            <img src="https://static.strengthlevel.com/images/exercises/machine-seated-crunch/machine-seated-crunch-800.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Espalda Baja en Máquina', this)">
                            Espalda Baja en Máquina
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2016/10/S2ABB_Ex_F_2.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Abdominales en Banco Abdominal 4x12', this)">
                            Abdominales en Banco Abdominal 4x12
                            <img src="https://contents.mediadecathlon.com/m10766761/k$6bdf2c6869bf06a99ca9a62e5aabd7b1/sq/250x250/Banco-De-Musculacion-Para-Abdominales-Vital-Gym-ES-507.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Abdominales en Banco de Crunches 4x12', this)">
                            Abdominales en Banco de Crunches 4x12
                            <img src="https://bodysolidmexico.com/wp-content/uploads/2020/07/BFHYP10r_Hero-Folded_DSF2125-2000.jpg" alt="image">
                        </button>

                        <button type="button" class="exercise-buttonDos" onclick="toggleExercise('Sabado', 'Abdominales en Banco de Flexor 4x12', this)">
                            Abdominales en Banco de Flexor 4x12
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTYkIjc3YUc1sRkYLDZJtQaEJ0DZUkE1sgP0Q&s" alt="image">
                        </button>

                    </div>

                    <div class="selected-exercises" id="selected-exercises-Sabado" style="display: none;">
                        <ul></ul>
                    </div>
                </div>


            </form>
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

    <script>
        const selectedExercises = JSON.parse('<?php echo isset($routine) ? $routine['ejerciciosSeleccionados'] : '[]'; ?>');

        document.addEventListener('DOMContentLoaded', () => {
            for (const day in selectedExercises) {
                if (selectedExercises[day].length > 0) {
                    selectedExercises[day].forEach(exercise => {
                        const button = document.querySelector(`button[onclick="toggleExercise('${day}', '${exercise}', this)"]`);
                        if (button) {
                            button.classList.add('selected');
                        }
                        renderSelectedExercises(day);
                    });
                }
            }
            updateHiddenInput();
        });

        function toggleExercise(day, exercise, button) {
            const list = selectedExercises[day] || [];
            const index = list.indexOf(exercise);

            if (index > -1) {
                list.splice(index, 1);
                button.classList.remove('selected');
            } else {
                list.push(exercise);
                button.classList.add('selected');
            }

            selectedExercises[day] = list;
            renderSelectedExercises(day);
            updateHiddenInput();
        }

        function renderSelectedExercises(day) {
            const container = document.getElementById(`selected-exercises-${day}`).querySelector('ul');
            container.innerHTML = '';
            selectedExercises[day].forEach(exercise => {
                const li = document.createElement('li');
                li.textContent = exercise;
                container.appendChild(li);
            });
        }

        function updateHiddenInput() {
            const input = document.getElementById('ejerciciosSeleccionados');
            input.value = JSON.stringify(selectedExercises);
        }


        function mostrarPrevisualizacion() {
            const form = document.getElementById('routineForm');
            const formData = new FormData(form);

            // Agregar los ejercicios seleccionados
            const selectedExercises = {};
            document.querySelectorAll('.day-container').forEach(dayContainer => {
                const day = dayContainer.querySelector('.day-title').innerText;
                const titleInput = dayContainer.querySelector('input[name^="title_routineday_"]');
                const title = titleInput ? titleInput.value : '';
                const exercises = [];


                dayContainer.querySelectorAll('.exercise-button.selected, .exercise-buttonDos.selected').forEach(button => {
                    exercises.push(button.innerText.trim());
                });
                if (exercises.length > 0) {
                    selectedExercises[day] = {
                        title,
                        exercises
                    };
                }
            });

            formData.append('ejerciciosSeleccionados', JSON.stringify(selectedExercises));

            // Convertir FormData a URLSearchParams
            const params = new URLSearchParams();
            for (const pair of formData.entries()) {
                params.append(pair[0], pair[1]);
            }

            // Abrir una nueva ventana para previsualizar el PDF
            window.open('preview-routine.php?' + params.toString(), '_blank');
        }
    </script>
</body>

</html>