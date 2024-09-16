<?php
require 'FPDF/fpdf.php';
require 'vendor/phpqrcode/qrlib.php';

class PDF extends FPDF {
    function Header() {
        // Aquí se puede agregar un encabezado personalizado
    }
}

// Conectamos a la base de datos y recuperamos los datos del estudiante
$ci = $_GET['ci']; // Cédula de identidad del estudiante
include 'config/database.php';

try {
    // Crear conexión usando PDO
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Consulta para obtener datos del estudiante, la facultad y la modalidad desde la tabla carrera
$sql = "SELECT e.id_cedula, e.fotografia, e.nombre_estudiante, c.modalidad, f.nombre_facultad, c.nombre_carrera
        FROM estudiante e
        JOIN matricula m ON e.id_cedula = m.id_cedula
        JOIN carrera c ON m.id_carrera = c.id_carrera
        JOIN facultad f ON c.id_facultad = f.id_facultad
        WHERE e.id_cedula = :ci";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':ci', $ci, PDO::PARAM_STR);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Estudiante no encontrado.");
}

$stmt->closeCursor();
$conn = null; // Cerrar la conexión

// Crear el PDF
$pdf = new PDF('P', 'pt', array(1365, 2427)); // Dimensiones en puntos (pt)
$pdf->AddPage();

// Agregar FOTO de fondo
$fotoPath = 'uploads/' . $student['fotografia']; // Asegúrate de que $student['fotografia'] tenga una extensión válida

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$extension = pathinfo($fotoPath, PATHINFO_EXTENSION);

if (in_array($extension, $allowedExtensions) && file_exists($fotoPath)) {
    $pdf->Image($fotoPath, 440, 750, 600, 650);
} else {
    die("La imagen no se pudo cargar correctamente o no es una extensión válida.");
}

// Agregar QR code
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

// Agregar imagen de fondo
$fondoPath = 'fondo2.png'; // Ruta de la imagen de fondo

if (file_exists($fondoPath)) {
    $pdf->Image($fondoPath, 0, 0, 1365, 2427); // Ajustar la imagen para que ocupe todo el fondo
}

// Información del estudiante
// Nombre
$pdf->SetFont('Times', 'Bu', 90);
$pdf->SetTextColor(0, 0, 0); // NEGRO
$pdf->SetXY(90, 1380);
$pageWidth = $pdf->GetPageWidth();
$nombreWidth = $pdf->GetStringWidth(utf8_decode($student['nombre_estudiante']));
$nombreX = ($pageWidth - $nombreWidth) / 2;
$pdf->SetXY($nombreX, $pdf->GetY() + 50); // Ajustar Y según necesites
$pdf->Cell($nombreWidth, 50, utf8_decode($student['nombre_estudiante']), 0, 1, 'C');

// Cédula
$pdf->SetFont('Times', 'I', 90);
$cedulaTexto = $student['id_cedula'];
$cedulaWidth = $pdf->GetStringWidth(utf8_decode($cedulaTexto));
$cedulaX = ($pageWidth - $cedulaWidth) / 2;
$pdf->SetXY($cedulaX, $pdf->GetY() + 50); // Ajustar Y según necesites
$pdf->Cell($cedulaWidth, 50, utf8_decode($cedulaTexto), 0, 1, 'C');

// Rol
$pdf->SetFont('Times', 'I', 50);
$pdf->SetTextColor(58, 58, 58); // Plomo
$pdf->SetXY(110, 1600);
$pdf->Cell(0, 50, utf8_decode('Rol: ') . utf8_decode("Estudiante"), 0, 1);

// Modalidad (desde la tabla carrera)
$pdf->SetFont('Times', 'I', 50);
$pdf->SetTextColor(58, 58, 58); // Plomo
$pdf->SetXY(110, 1650);
$pdf->Cell(0, 50, utf8_decode('Modalidad: ') . utf8_decode($student['modalidad']), 0, 1);

// Facultad (con MultiCell para manejar texto extenso)
$pdf->SetXY(round(110), round(1700));
$pdf->MultiCell(round(1200), round(50), utf8_decode('Facultad: ') . utf8_decode($student['nombre_facultad']), 0, 'L');

// Carrera (extraída de la tabla `carrera`)
$pdf->SetX(round(110)); // Restablece la posición X a 110 para la siguiente línea
$pdf->Cell(round(0), round(50), utf8_decode('Carrera: ') . utf8_decode($student['nombre_carrera']), 0, 1);

// Descargar o visualizar el PDF
$pdfFile = 'carnet_digital.pdf';
$pdf->Output('F', $pdfFile);

$action = $_GET['action'] ?? '';

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
