<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:../index.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "No se proporcionó el ID de la rutina.";
    exit();
}

include 'actions/dbcon.php';

$routine_id = $_GET['id'];
$qry = "SELECT * FROM routines WHERE routine_id = '$routine_id'";
$result = mysqli_query($con, $qry);
$routine = mysqli_fetch_assoc($result);

if (!$routine) {
    echo "Rutina no encontrada.";
    exit();
}

// Decodificar los ejercicios seleccionados
$ejerciciosSeleccionados = json_decode($routine['ejerciciosSeleccionados'], true);

require_once('../vendor/autoload.php'); // Incluir el autoload de Composer

// Crear una nueva instancia de TCPDF
class MYPDF extends TCPDF
{
    // Función para agregar la marca de agua de imagen
    public function Header()
    {
        $image_file = '../img/tres.png'; // Ruta de la imagen de la marca de agua
        $this->Image($image_file, 30, 75, 150, 0, 'PNG', '', '', false, 300, '', false, false, 0);
    }

    // Override footer method to do nothing
    public function Footer() {}
}

// Crear una nueva instancia de MYPDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar el documento PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Administrador del sistema de gimnasio');
$pdf->SetTitle('Rutina Personalizada');
$pdf->SetSubject('Rutina Personalizada');
$pdf->SetKeywords('Rutina, Gimnasio, Ejercicio');

// Desactivar la línea de cabecera
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(false);

// Agregar una página
$pdf->AddPage();
$pdf->Image('../img/iron_strong5.png', 128, 5, 85, '', 'PNG');
$stylesheet = file_get_contents('../css/pdf_styles.css');

$html = "
<style>
    $stylesheet
</style>
<div class='header-container'>
    <div class='header-right'>
        <h6><strong class='bold'>Entrenador:</strong> Arturo Osorio Ramirez</h6>
        <h6><strong class='bold'>No. de contacto:</strong> 2381130605</h6>
    </div>
</div>
<div style='clear: both;'></div>
";

$pdf->writeHTML($html, true, false, true, false, '');

// Establecer la posición Y para el contenido de la izquierda
$pdf->SetY(0);

$html = "<div class='header-left'>
        <h1><strong class='bold'>Rutina: </strong>" . $routine['routine_name'] . "</h1>
        <p><strong class='bold'>Dirigida a: </strong>" . $routine['fullname'] . "</p>
        <p><strong class='bold'>Fecha: </strong>" . $routine['dor'] . "</p>
        <p><strong class='bold'>Servicio: </strong>" . $routine['services'] . "</p>
        <div style='clear: both;'></div>
        ";

$pdf->writeHTML($html, true, false, true, false, '');

// Verificar si ejerciciosSeleccionados no está vacío
$pdf->SetY($pdf->GetY() - 8);

$html = '';

// Obtener los títulos de los días de la rutina
$titulos = [
    'Lunes' => $routine['title_routineday_Lunes'],
    'Martes' => $routine['title_routineday_Tuesday'],
    'Miercoles' => $routine['title_routineday_Wednesday'],
    'Jueves' => $routine['title_routineday_Thursday'],
    'Viernes' => $routine['title_routineday_Friday'],
    'Sabado' => $routine['title_routineday_Saturday']
];

if (!empty($ejerciciosSeleccionados)) {
    // Dividir los días en dos columnas
    $half = ceil(count($ejerciciosSeleccionados) / 2);
    $leftColumn = array_slice($ejerciciosSeleccionados, 0, $half, true);
    $rightColumn = array_slice($ejerciciosSeleccionados, $half, null, true);

    $html .= "<table><tr><td width='50%'>";
    foreach ($leftColumn as $dia => $ejercicios) {
        $titulo = isset($titulos[$dia]) ? $titulos[$dia] : '';
        $html .= "<h4>$dia - $titulo</h4>";
        $html .= "<ul class='exercise-list'>";
        foreach ($ejercicios as $ejercicio) {
            $html .= "<li class='exercise-item'>$ejercicio</li>";
        }
        $html .= "</ul>";
    }
    $html .= "</td><td width='50%'>";
    foreach ($rightColumn as $dia => $ejercicios) {
        $titulo = isset($titulos[$dia]) ? $titulos[$dia] : '';
        $html .= "<h4>$dia - $titulo</h4>";
        $html .= "<ul class='exercise-list'>";
        foreach ($ejercicios as $ejercicio) {
            $html .= "<li class='exercise-item'>$ejercicio</li>";
        }
        $html .= "</ul>";
    }
    $html .= "</td></tr></table>";
} else {
    $html = "<p>No se seleccionaron ejercicios.</p>";
}

$pdf->writeHTML($html, true, false, true, false, '');

// Cerrar y mostrar el PDF
$pdf->Output('rutina_personalizada.pdf', 'I');
