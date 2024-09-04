<?php
require 'FPDF/fpdf.php';
require 'vendor/phpqrcode/qrlib.php';

class PDF extends FPDF {
    function Header() {
        // Aquí se puede agregar un encabezado
    }
}

// Conectamos a la base de datos y recuperamos los datos del estudiante
$ci = $_GET['ci'];
include 'config/database.php';

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT e.*, f.nombre_facultad, c.nombre_carrera, cl.nombre_ciclo, p.fecha_inicio, p.fecha_fin 
        FROM estudiante e
        JOIN facultad f ON e.id_facultad = f.id_facultad
        JOIN carrera c ON e.id_carrera = c.id_carrera
        JOIN ciclo cl ON e.id_ciclo = cl.id_ciclo
        JOIN periodo p ON e.id_periodo = p.id_periodo
        WHERE e.id_cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ci);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    die("Estudiante no encontrado.");
}

$stmt->close();
$conn->close();

$fecha_inicio = DateTime::createFromFormat('Y-m-d', $student['fecha_inicio']);
$fecha_fin = DateTime::createFromFormat('Y-m-d', $student['fecha_fin']);
$periodo_academico = $fecha_inicio->format('F Y') . ' - ' . $fecha_fin->format('F Y');

$pdf = new PDF('P', 'pt', array(1365, 2427)); // Dimensiones en puntos (pt)
$pdf->AddPage();

// Agregar FOTO de fondo
$fotoPath = 'uploads/' . $student['fotografia'];

if (file_exists($fotoPath)) {
    $pdf->Image($fotoPath, 440, 750, 600, 650);
} else {
    die("La imagen no se pudo cargar correctamente.");
}

// Agregar qr fondo
$qrData = 'http://localhost:8081/Carnet/src/validacion.php?ci=' . urlencode($ci);
$qrFile = 'qrcode.png';
QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 10);

$qrImage = imagecreatefrompng($qrFile);
$logo = imagecreatefrompng('logo.png');
$qrWidth = imagesx($qrImage);
$qrHeight = imagesy($qrImage);
$logoWidth = imagesx($logo);
$logoHeight = imagesy($logo);

$newLogoWidth = $qrWidth / 5;
$newLogoHeight = $logoHeight * ($newLogoWidth / $logoWidth);
$logoResized = imagecreatetruecolor($newLogoWidth, $newLogoHeight);
imagecopyresampled($logoResized, $logo, 0, 0, 0, 0, $newLogoWidth, $newLogoHeight, $logoWidth, $logoHeight);

$logoX = ($qrWidth - $newLogoWidth) / 2;
$logoY = ($qrHeight - $newLogoHeight) / 2;
imagecopymerge($qrImage, $logoResized, $logoX, $logoY, 0, 0, $newLogoWidth, $newLogoHeight, 100);

imagepng($qrImage, $qrFile);
imagedestroy($qrImage);
imagedestroy($logo);
imagedestroy($logoResized);

$pdf->Image($qrFile, 0, 1900, 500, 550);


$fondoPath = 'fondo2.png'; // Ruta de la imagen de fondo

if (file_exists($fondoPath)) {
    $pdf->Image($fondoPath, 0, 0, 1365, 2427); // Ajustar la imagen para que ocupe todo el fondo
}

// Información del estudiante
//Nombre
$pdf->SetFont('Times', 'Bu', 90);
$pdf->SetTextColor(0, 0, 0); // NEGRO
// Obtener el ancho de la página
$pdf->SetXY(90, 1380);
$pageWidth = $pdf->GetPageWidth();
// Obtener el ancho del texto del nombre del estudiante
$nombreWidth = $pdf->GetStringWidth(utf8_decode($student['nombre_estudiante']));
// Calcular la posición X para centrar el texto
$nombreX = ($pageWidth - $nombreWidth) / 2;
// Establecer la posición para el nombre centrado
$pdf->SetXY($nombreX, $pdf->GetY() + 50); // Ajustar Y según necesites
$pdf->Cell($nombreWidth, 50, utf8_decode($student['nombre_estudiante']), 0, 1, 'C');

// Cédula
$pdf->SetFont('Times', 'I', 90);
/// Obtener el texto completo para la cédula
$cedulaTexto =  $student['id_cedula'];
// Obtener el ancho del texto de la cédula
$cedulaWidth = $pdf->GetStringWidth(utf8_decode($cedulaTexto));
// Calcular la posición X para centrar la cédula
$cedulaX = ($pageWidth - $cedulaWidth) / 2;
// Establecer la posición para la cédula centrada justo debajo del nombre
$pdf->SetXY($cedulaX, $pdf->GetY() + 50); // Ajustar Y según necesites
$pdf->Cell($cedulaWidth, 50, utf8_decode($cedulaTexto), 0, 1, 'C');



$pdf->SetFont('Times', 'I', 50);
$pdf->SetTextColor(58, 58, 58); // plomo

// Rol
$pdf->SetXY(110, 1600);
$pdf->Cell(0, 50, utf8_decode('Rol: ') . utf8_decode($student['rol']), 0, 1);

// Modalidad
$pdf->SetXY(110, 1650);
$pdf->Cell(0, 50, utf8_decode('Modalidad: ') . utf8_decode($student['modalidad']), 0, 1);

// Facultad (con MultiCell para manejar texto extenso)
$pdf->SetXY(110, 1700);
$pdf->MultiCell(1200, 50, utf8_decode('Facultad: ') . utf8_decode($student['nombre_facultad']), 0, 'L');

// Reajustar la posición X antes de imprimir "Carrera"
$pdf->SetX(110); // Restablece la posición X a 90 para la siguiente línea
$pdf->Cell(0, 50, utf8_decode('Carrera: ') . utf8_decode($student['nombre_carrera']), 0, 1);


//DESCARGAR
$pdfFile = 'carnet_digital.pdf';
$pdf->Output('F', $pdfFile);

$action = $_GET['action'];

if ($action == 'download') {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="carnet_digital.pdf"');
    readfile($pdfFile);
    unlink($pdfFile);
    exit();
} else if ($action == 'view') {
    header('Content-Type: application/pdf');
    readfile($pdfFile);
    unlink($pdfFile);
    exit();
}
?>

