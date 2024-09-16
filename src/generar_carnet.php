<?php
require 'FPDF/fpdf.php';
require 'vendor/phpqrcode/qrlib.php';

class PDF extends FPDF {
    function Header() {
        // Encabezado personalizado (si es necesario)
    }
}

// Cédula de identidad del estudiante
$ci = $_GET['ci'];

include 'config/database.php';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Consulta para obtener datos del estudiante
$sql = "SELECT e.id_cedula, e.fotografia, e.nombre_estudiante, c.modalidad, f.nombre_facultad, c.nombre_carrera
        FROM estudiante e
        JOIN matricula m ON e.id_cedula = m.id_cedula
        JOIN carrera c ON m.id_carrera = c.id_carrera
        JOIN facultad f ON c.id_facultad = f.id_facultad
        WHERE e.id_cedula = ?";
$stmt = $conn->prepare($sql);
$stmt->bindValue(1, $ci, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    $student = $result[0];
} else {
    die("Estudiante no encontrado.");
}

// Cerrar conexión
$stmt->closeCursor();
$conn = null;

// Crear el PDF
$pdf = new PDF('P', 'pt', array(1365, 2427));
$pdf->AddPage();

// Agregar FOTO de fondo
$fotoPath = 'uploads/' . $student['fotografia'];
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$extension = pathinfo($fotoPath, PATHINFO_EXTENSION);

if (in_array($extension, $allowedExtensions) && file_exists($fotoPath)) {
    $pdf->Image($fotoPath, intval(440), intval(750), intval(600), intval(650));
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
$logoResized = imagecreatetruecolor(intval($newLogoWidth), intval($newLogoHeight));
imagecopyresampled($logoResized, $logo, 0, 0, 0, 0, intval($newLogoWidth), intval($newLogoHeight), $logoWidth, $logoHeight);

$logoX = ($qrWidth - $newLogoWidth) / 2;
$logoY = ($qrHeight - $newLogoHeight) / 2;
imagecopymerge($qrImage, $logoResized, intval($logoX), intval($logoY), 0, 0, intval($newLogoWidth), intval($newLogoHeight), 100);

imagepng($qrImage, $qrFile);
imagedestroy($qrImage);
imagedestroy($logo);
imagedestroy($logoResized);

$pdf->Image($qrFile, intval(0), intval(1900), intval(500), intval(550));

// Agregar imagen de fondo
$fondoPath = 'fondo2.png'; // Ruta de la imagen de fondo

if (file_exists($fondoPath)) {
    $pdf->Image($fondoPath, intval(0), intval(0), intval(1365), intval(2427)); // Ajustar la imagen para que ocupe todo el fondo
}

// Información del estudiante
$pdf->SetFont('Times', 'Bu', 90);
$pdf->SetTextColor(0, 0, 0); // NEGRO
$pdf->SetXY(intval($nombreX), intval($pdf->GetY() + 50));
$pdf->Cell(intval($nombreWidth), 50, utf8_decode($student['nombre_estudiante']), 0, 1, 'C');

// Cédula
$pdf->SetFont('Times', 'I', 90);
$cedulaTexto = $student['id_cedula'];
$pdf->SetXY(intval($cedulaX), intval($pdf->GetY() + 50));
$pdf->Cell(intval($cedulaWidth), 50, utf8_decode($cedulaTexto), 0, 1, 'C');

// Rol
$pdf->SetFont('Times', 'I', 50);
$pdf->SetTextColor(58, 58, 58); // Plomo
$pdf->SetXY(intval(110), intval(1600));
$pdf->Cell(0, 50, utf8_decode('Rol: ') . utf8_decode("Estudiante"), 0, 1);

// Modalidad (desde la tabla carrera)
$pdf->SetFont('Times', 'I', 50);
$pdf->SetTextColor(58, 58, 58); // Plomo
$pdf->SetXY(intval(110), intval(1650));
$pdf->Cell(0, 50, utf8_decode('Modalidad: ') . utf8_decode($student['modalidad']), 0, 1);

// Facultad (con MultiCell para manejar texto extenso)
$pdf->SetXY(intval(110), intval(1700));
$pdf->MultiCell(intval(1200), intval(50), utf8_decode('Facultad: ') . utf8_decode($student['nombre_facultad']), 0, 'L');

// Carrera (extraída de la tabla `carrera`)
$pdf->SetX(intval(110)); // Restablece la posición X a 110 para la siguiente línea
$pdf->Cell(intval(0), intval(50), utf8_decode('Carrera: ') . utf8_decode($student['nombre_carrera']), 0, 1);

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
