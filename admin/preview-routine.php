<?php
// Habilitar el registro de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../vendor/autoload.php');

// Recoger los datos del formulario y manejar posibles ausencias
$fullname = isset($_GET['fullname']) ? $_GET['fullname'] : '';
$routine_name = isset($_GET['routine_name']) ? $_GET['routine_name'] : '';
$contact = isset($_GET['contact']) ? $_GET['contact'] : '';
$dor = isset($_GET['dor']) ? $_GET['dor'] : '';
$gmail = isset($_GET['gmail']) ? $_GET['gmail'] : '';
$services = isset($_GET['services']) ? $_GET['services'] : '';
$ejerciciosSeleccionados = json_decode($_GET['ejerciciosSeleccionados'], true);

// Crear una nueva instancia de TCPDF con marca de agua
class MYPDF extends TCPDF
{
    // Función para agregar la marca de agua de imagen
    public function Header()
    {
        $image_file = '../img/tres.png'; // Ruta de la imagen de la marca de agua
        $this->Image($image_file, 30, 75, 150, 0, 'PNG', '', '', false, 300, '', false, false, 0);
    }

    // Override footer method to do nothing
    public function Footer()
    {
        // No footer required
    }
}

// Crear una nueva instancia de MYPDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar el documento PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Administrador del sistema de gimnasio');
$pdf->SetTitle('Previsualización de Rutina Personalizada');
$pdf->SetSubject('Rutina Personalizada');
$pdf->SetKeywords('Rutina, Gimnasio, Ejercicio');

// Desactivar la línea de cabecera
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(false);

// Agregar una página
$pdf->AddPage();

// Insertar una imagen en la cabecera (ajusta la ruta y el tamaño según sea necesario)
$pdf->Image('../img/iron_strong5.png', 128, 5, 85, '', 'PNG');

// Cargar el contenido del archivo CSS
$stylesheet = file_get_contents('../css/pdf_styles.css');

// Verificar si se ha cargado correctamente el contenido del archivo CSS
if ($stylesheet === false) {
    die('Error al cargar el archivo CSS');
}

// Establecer contenido con estilos
$html = "
<style>
    $stylesheet
    ul {
        list-style-type: none;
        padding: 0;
    }
    ul li::before {
        content: '\\2022'; /* Punto de viñeta */
        padding-right: 5px;
    }
</style>
<div class='header-container'>
    <div class='header-right'>
        <h6><strong class='bold'>Entrenador:</strong> Arturo Osorio Ramirez</h6>
        <h6><strong class='bold'>No. de contacto:</strong> 2381130605</h6>
    </div>
</div>
<div style='clear: both;'></div>
";

// Escribir contenido al PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Establecer la posición Y para el contenido de la izquierda
$pdf->SetY(0); // Ajusta este valor según sea necesario

$html = "
<div class='header-left'>
    <h1><strong class='bold'>Rutina:</strong> $routine_name</h1>
    <p><strong class='bold'>Dirigida a:</strong> $fullname</p>
    <p><strong class='bold'>Fecha:</strong> $dor</p>
    <p><strong class='bold'>Servicio:</strong> $services</p>
</div>
<div style='clear: both;'></div>
";

// Escribir contenido al PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Establecer la posición Y para los ejercicios
$pdf->SetY($pdf->GetY() - 18); // Ajusta este valor según sea necesario para reducir el espacio

if (!empty($ejerciciosSeleccionados)) {
    // Dividir los días en dos columnas
    $half = ceil(count($ejerciciosSeleccionados) / 2);
    $leftColumn = array_slice($ejerciciosSeleccionados, 0, $half, true);
    $rightColumn = array_slice($ejerciciosSeleccionados, $half, null, true);

    $html = "<table><tr><td width='50%'>";
    foreach ($leftColumn as $dia => $data) {
        $titulo = isset($data['title']) ? $data['title'] : '';
        $ejercicios = isset($data['exercises']) ? $data['exercises'] : [];
        $html .= "<h4>$dia - $titulo</h4>";
        $html .= "<ul class='exercise-list'>";
        foreach ($ejercicios as $ejercicio) {
            $html .= "<li class='exercise-item'>$ejercicio</li>";
        }
        $html .= "</ul>";
    }
    $html .= "</td><td width='50%'>";
    foreach ($rightColumn as $dia => $data) {
        $titulo = isset($data['title']) ? $data['title'] : '';
        $ejercicios = isset($data['exercises']) ? $data['exercises'] : [];
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
$pdf->Output('previsualizacion_rutina.pdf', 'I');
?>
